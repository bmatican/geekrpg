#ifndef JUDGE_H
#define JUDGE_H

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
#include "constants.h"
#include "diff.h"
#include "path.h"
#include "wait.h"

// FUNCTION IMPLEMENTATIONS
program_state compile(char* programfile, char* outputprogram) {
  char* args[] = {"/usr/bin/g++", programfile, "-o", outputprogram,  NULL};

  execv("/usr/bin/g++", args);
  criticalFail("Error in exec on compile");
  // return is irrelevant here
  return STATE_COMPILE_ERROR;  
}

// the return is irelevant since it is executed in a child process...
program_state execute(char* program, char* input, char* output, int timeLimitMS, int heapLimitMB, int stackLimitMB) {
  program_state state = STATE_CORRECT;
  const rlim_t heapL = (long) heapLimitMB * 1024L * 1024L;
  const rlim_t stackL = (long) stackLimitMB * 1024L * 1024L;
  struct rlimit heap, stack;
  int result;
  result = getrlimit(RLIMIT_AS, &heap);
  if (0 == result) {
    heap.rlim_cur = heapL;
    heap.rlim_max = heapL;
    result = setrlimit(RLIMIT_AS, &heap);
    if (0 != result) {
      criticalFail("Could not set HEAP memory limit");
      //logError("Could not set HEAP memory limit for", program);
      //state = STATE_INTERNAL_ERROR;
    }
  } else  {
    criticalFail("Could not determine HEAP memory limit");
    //logError("Could not determine HEAP memory limit on", program);
    //state = STATE_INTERNAL_ERROR;
  }
  
  result = getrlimit(RLIMIT_STACK, &heap);
  if (0 == result) {
    stack.rlim_cur = stackL;
    stack.rlim_max = stackL;
    result = setrlimit(RLIMIT_STACK, &stack);
    if (0 != result) {
      criticalFail("Could not set STACK memory limit for");
      //logError("Could not set STACK memory limit for", program);
      //state = STATE_INTERNAL_ERROR;
    }
  } else  {
    criticalFail("Could not determine STACK memory limit on");
    //logError("Could not determine STACK memory limit on", program);
    //state = STATE_INTERNAL_ERROR;
  }
  
  // timing issues
  struct itimerval timer;
  timer.it_interval.tv_sec = timeLimitMS / 1000;
  timer.it_interval.tv_usec = 1000 * (timeLimitMS % 1000);
  timer.it_value = timer.it_interval;
  if (0 != setitimer(ITIMER_REAL, &timer, NULL)) {
    criticalFail("Error on setitimer");
    //logError("Could not set timer", program);
    //state = STATE_INTERNAL_ERROR;
  }
  
  char command[255];
  (void) sprintf(command, "%s < %s > %s 2> /dev/null", program, input, output );
  char* args[] = {"/bin/bash", "-c", command, NULL};
  
  execv("/bin/bash", args);
  criticalFail("Error in exec on running");
  //logError("Error in exec on running", program);
  //state = STATE_INTERNAL_ERROR;
  
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
program_state judge(int jobid) {
  // TODO: read from DB
  char* problemname = "__test";
  char* language = "cpp";
  int nrtests = 1;
  int timeLimitMS = 5000;
  int heapLimitMB = 32;
  int stackLimitMB = 64;
  int hasTester = 0;
  
  // default state
  program_state state = STATE_CORRECT;
  
  char id_str[10], source[MAX_PATH_LENGTH], executable[MAX_PATH_LENGTH];
  // jobid as string
  (void) sprintf(id_str, "%d", jobid);
  pathJobExecutable(executable, jobid);
  pathJobSource(source, jobid, language);

  pid_t pid_compile = fork();
  if (pid_compile == 0) {
    (void) compile(source, executable);
  } else if (0 < pid_compile) {
    // check if properly compiled
    state = waitChild(pid_compile);
    if (STATE_CORRECT == state) {
      // should loop for all tests
      int testNr, error = 0, outcome[nrtests];
      for (testNr = 0; testNr < nrtests; ++testNr) {
        char input[MAX_PATH_LENGTH], output[MAX_PATH_LENGTH];
        // input and output for program execution
        pathProblemInput(input, problemname, testNr);
        pathJobOutput(output, jobid, testNr);
        pid_t pid_run = fork();
        if (pid_run == 0) {
          ptrace(PTRACE_TRACEME, 0, 0, 0);
          (void) execute(executable, input, output, timeLimitMS, heapLimitMB, stackLimitMB);
        } else if (0 < pid_run) {
          // check if properly executed
          state = timedWaitChild(pid_run, timeLimitMS);
          if (STATE_CORRECT != state) {
            // signal at least one error happened
            error = 1;
          } else {
            // check for output correctness
            if (hasTester) {
              state = checkCorrectWithTester(problemname, input, output);
            } else {
              char actualOutput[MAX_PATH_LENGTH];
              pathProblemOutput(actualOutput, problemname, testNr);
              state = checkCorrectWithoutTester(actualOutput, output);
            }
          }
          // remove output
          remove(output);
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
      remove(executable);
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

#endif
