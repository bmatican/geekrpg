// SYSTEM INCLUDES
#include <signal.h>
#include <unistd.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>

#include <sys/ptrace.h>
#include <sys/types.h>
#include <sys/resource.h>
#include <sys/time.h>

// LOCAL INCLUDES
#include "globals.h"
#include "diff.h"
#include "path.h"
#include "wait.h"
#include "problem.h"

program_state
compile(
  problem p
) {
  char* args[] = {"/usr/bin/g++", p.file_source, "-o", p.file_exec,  NULL};

  execv("/usr/bin/g++", args);
  criticalFail("Error in exec on compile");
  // return is irrelevant here
  return STATE_COMPILE_ERROR;  
}

// the return is irelevant since it is executed in a child process...
program_state execute(problem p) {
  program_state state = STATE_CORRECT;
  const rlim_t heapL = (long) p.heapLimitMB * 1024L * 1024L;
  const rlim_t stackL = (long) p.stackLimitMB * 1024L * 1024L;
  struct rlimit heap, stack;
  int result;
  result = getrlimit(RLIMIT_AS, &heap);
  if (0 == result) {
    heap.rlim_cur = heapL;
    heap.rlim_max = heapL;
    result = setrlimit(RLIMIT_AS, &heap);
    if (0 != result) {
      criticalFail("Could not set HEAP memory limit");
    }
  } else  {
    criticalFail("Could not determine HEAP memory limit");
  }
  
  /*
  result = getrlimit(RLIMIT_STACK, &stack);
  if (0 == result) {
    stack.rlim_cur = stackL;
    stack.rlim_max = stackL;
    result = setrlimit(RLIMIT_STACK, &stack);
    if (0 != result) {
      criticalFail("Could not set STACK memory limit for");
    }
  } else  {
    criticalFail("Could not determine STACK memory limit on");
  }
  */
  
  // timing issues
  struct itimerval timer;
  timer.it_interval.tv_sec = p.timeLimitMS / 1000;
  timer.it_interval.tv_usec = 1000 * (p.timeLimitMS % 1000);
  timer.it_value = timer.it_interval;
  if (0 != setitimer(ITIMER_REAL, &timer, NULL)) {
    criticalFail("Error on setitimer");
  }
  
  char command[255];
  (void) sprintf(command, "%s < %s > %s 2> %s", p.file_exec, p.file_stdin, p.file_stdout, p.file_stderr);
  char* args[] = {"/bin/bash", "-c", command, NULL};
  
  execv("/bin/bash", args);
  criticalFail("Error in exec on running");
  
  // should return ...
  return state;
}

/**
 * Executes a judging job against a specific solution from the database.
 * Needs to have the following defined:
 * <p>
 *  DB_USER
 *  DB_PASSWORD
 *  DB_DATABASE
 * </p>
 *
 * @param int jobid the job to executed
 * @return program_state the state of the overall execution
 */
program_state 
judge(
  int jobid
) {
  // jobid as string
  char id_str[10];
  (void) sprintf(id_str, "%d", jobid);
  
  // default state
  program_state state = STATE_CORRECT;
  
  // TODO: read from DB
  problem p;
  
  char* problemname = "__test";
  char* language = "cpp";
  int nrtests = 1;
  char hasTester = 0;
  int timeLimitMS = 5000;
  int heapLimitMB = 32;
  int stackLimitMB = 64;
  
  problem_new(&p, jobid, problemname, language, nrtests, hasTester);
  problem_limits(&p, timeLimitMS, heapLimitMB, stackLimitMB);
  
  pid_t pid_compile = fork();
  if (pid_compile == 0) {
    (void) compile(p);
  } else if (0 < pid_compile) {
    // check if properly compiled
    state = waitChild(pid_compile);
    if (STATE_CORRECT == state) {
      // should loop for all tests
      int testNr, error = 0, outcome[nrtests];
      for (testNr = 0; testNr < nrtests; ++testNr) {
        // set input and output for program execution
        problem_test(&p, testNr);
        pid_t pid_run = fork();
        if (pid_run == 0) {
          ptrace(PTRACE_TRACEME, 0, 0, 0);
          (void) execute(p);
        } else if (0 < pid_run) {
          // check if properly executed
          state = timedWaitChild(pid_run, p);
          if (STATE_CORRECT != state) {
            // signal at least one error happened
            error = 1;
          } else {
            // check for output correctness
            state = checkCorrect(p);
          }
          // remove output
          remove(p.file_stdout);
        } else {
          criticalFail("Error on executing task");
          //logError("Fork to run failed on job", id_str);
          //state = STATE_INTERNAL_ERROR;
        }
        outcome[testNr] = state; // marking
      }
      
      //TODO: this is for testing...
      // after all tests are run, if we have had an error
      if (1 == error) {
        (void) printf("We had errors...\n");
      } else {
        (void) printf("No errors!\n");
      }
      
      //TODO: save state...
      // loop through outcome
      
      // cleanup binary
      remove(p.file_exec);
    } else { // fail
      (void) fprintf(stderr, "Problems on compiling on job %s\n", id_str);
      state = STATE_COMPILE_ERROR;
    }
  } else {
    criticalFail("Error on fork for compile");
    //logError("Fork to compile failed on job", id_str);
    //state = STATE_INTERNAL_ERROR;
  }
  
  return state;
}
