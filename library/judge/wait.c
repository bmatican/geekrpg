// GLOBAL INCLUDES
#include <stdlib.h>
#include <string.h>
#include <errno.h>
#include <sys/ptrace.h>
#include <sys/wait.h>
#include <sys/reg.h>
#include <sys/syscall.h>

// LOCAL INCLUDES
#include "globals.h"
#include "problem.h"
#include "wait.h"

program_state 
waitChild(
  pid_t pid
) {
  int exitstat;
  (void) waitpid(pid, &exitstat, 0);

  if (WIFSIGNALED(exitstat))
  {
    (void) fprintf(stderr, "Child exited due to signal %d\n", WTERMSIG(exitstat));
    return STATE_INTERNAL_ERROR;
  }
  
  if (!WIFEXITED(exitstat))
  {
     (void) fprintf(stderr, "Child exited with error %d\n", WEXITSTATUS(exitstat));
     return STATE_RETURN_NOT_ZERO;
  }
  
  return STATE_CORRECT;
}

program_state 
timedWaitChild(
  pid_t child, 
  problem p
) {
  program_state state = STATE_CORRECT;
  
  // use this for debugging
  char childstr[64];
  (void) sprintf(childstr, "%ld", (long) child);

  // syscall remembering mechanism
  long syscall; // value from ORIG_RAX or ORIG_EAX
  long cur_call = -1; // default non-existent
  char insyscall = 0; // if entering or exiting syscall
  
  //TODO: change mechanics if we no longer use bash -c
  int execcount = 0; // number of execs, one for bash, one for process
  
  // resource usage
  struct rusage ru, initru;
  // process registries
  // struct user_regs_struct regs;
  
  // values for waitpid
  int waitstatus;
  int waitresult;
  // execute once for initial resource offsetting
  waitresult = wait4(child, &waitstatus, 0, &ru);
  if (-1 == waitresult) 
  {
    criticalFail("Error on waitpid");
    //logError("Wait failed", child);
    //return STATE_INTERNAL_ERROR;
  }
  memcpy(&initru, &ru, sizeof(ru));

  // loop while not done
  char done = 0;
  do 
  {
    // do something with rusage, after eliminating initial
    // ru.ru_stime -= initru.ru_stime;
    // ru.ru_utime -= initru.ru_utime;
    
    //TODO: remove useless messages...they are just for debug now
    if (WIFSTOPPED(waitstatus)) 
    {
      switch(WSTOPSIG(waitstatus)) 
      {
      case SIGALRM: // real timer
        logError("Real time expired in process", childstr);
        ptrace(PTRACE_KILL, child, NULL, NULL);
        state = STATE_TIME_LIMIT_EXCEEDED;
        done = 1;
        break;
      case SIGXCPU: // CPU resource limit exceeded
      case SIGPROF: // profile timer expired
      case SIGVTALRM: // virtual timer expired
        logError("Virtual time expired in process", childstr);
        ptrace(PTRACE_KILL, child, NULL, NULL);
        state = STATE_TIME_LIMIT_EXCEEDED;
        done = 1;
        break;
      case SIGUSR1: // memory limit exceeded ... doesn't really work :p
        logError("Memory limit exceeded in process", childstr);
        ptrace(PTRACE_KILL, child, NULL, NULL);
        state = STATE_MEMORY_LIMIT_EXCEEDED;
        done = 1;
        break;
        /*
      case SIGSEGV:
        printf("Current syscall %ld\nExpected syscall %d\nCurrent errno %d\nExpected errno %d\n", cur_call, SYS_mmap, errno, ENOMEM);
        ptrace(PTRACE_KILL, child, NULL, NULL);
        state = STATE_MEMORY_LIMIT_EXCEEDED;
        done = 1;
        break;
        */
      case SIGXFSZ: // output file size exceeded
        logError("Output size exceeded in process", childstr);
        ptrace(PTRACE_KILL, child, NULL, NULL);
        state = STATE_OUTPUT_LIMIT_EXCEEDED;
        done = 1;
        break;
      case SIGTRAP: // ptrace trap
        // ptrace(PTRACE_GETREGS, child, NULL, &regs);
#ifdef __x86_64__
        syscall = ptrace(PTRACE_PEEKUSER, child, 8 * ORIG_RAX, NULL);
#else
        syscall = ptrace(PTRACE_PEEKUSER, child, 4 * ORIG_EAX, NULL);
#endif
        if (-1 == syscall) 
        {
          //criticalFail("Error on ptrace");
          logError("Error ptrace", childstr);
          state = STATE_INTERNAL_ERROR;
          done = 1;
          break;
        }

        char syscallstr[64];
        (void) sprintf(syscallstr, "%ld", syscall);
        if (SYS_execve == syscall) 
        {
          if (execcount++ < 2) 
          {
            break;
            // run /bin/bash
            // then run the actual program
          } 
          else 
          {
            logError("Illegal operation", syscallstr);
            ptrace(PTRACE_KILL, child, NULL, NULL);
            state = STATE_ILLEGAL_SYSCALL;
            done = 1;
            break;
          }
        }

        // not execv
        if (0 == insyscall) 
        {
          insyscall = 1;
          cur_call = syscall;
          printf("SYSCALL > %ld\n", syscall);
        } 
        else if (cur_call == syscall) 
        {
          insyscall = 0;
          printf("SYSCALL < %ld\n", syscall);
        }
        break;
      case SIGCHLD:
        // child ended, rerun the loop to get exit status info
        //printf("Current syscall %ld\nExpected syscall %d\nCurrent errno %d\nExpected errno %d\n", cur_call, SYS_mmap, errno, ENOMEM);
        printf("Child finished\n");
        break;
      default :
        printf("Unusual signal ...%d\n", WSTOPSIG(waitstatus));
        break;
      }
    } 
    else if (WIFSIGNALED(waitstatus)) 
    {
      printf("Child stopped via signal %d\n", WTERMSIG(waitstatus));
      break;
    } 
    else if (WIFEXITED(waitstatus)) 
    {
      printf("Child exited with status %d\n", WEXITSTATUS(waitstatus));
      break;
    }
    
    if (!done) 
    {
      // trace next call
      ptrace(PTRACE_SYSCALL, child, NULL, NULL);
      waitresult = wait4(child, &waitstatus, 0, &ru);
      if (-1 == waitresult) 
      {
        logError("Error waitpid", childstr);
        state = STATE_INTERNAL_ERROR;
        done = 1;
      }
    }
  }
  while (!done);

  return state;
}
