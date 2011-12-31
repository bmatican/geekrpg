#include <stdio.h>
#include <unistd.h>

#include "path.h"
#include "wait.h"
#include "diff.h"

program_state 
compareOutput(
  char* program, 
  char* given, 
  char* found
) {
  program_state state = STATE_CORRECT;
  pid_t pid = fork();
  if (0 == pid) 
  { // child
    char command[255];
    // -b ignores whitespace through lines , -B ignores just new lines
    (void) sprintf(command, "%s %s %s 2> /dev/null", program, given, found);
    char* args[] = {"/bin/bash", "-c", command, NULL};
    
    execv("/bin/bash", args);
    criticalFail("Error running diff");
  } 
  else if (0 < pid) 
  { // parent
    program_state state = waitChild(pid);
    if (STATE_CORRECT == state) 
    {
      state = STATE_CORRECT;
    } 
    else if (STATE_RETURN_NOT_ZERO == state) 
    {
      state = STATE_WRONG_ANSWER;
    } 
    else 
    {
      state = STATE_INTERNAL_ERROR;
    }
  } 
  else 
  { // fail
    criticalFail("Error forking for diff");
  }
  
  return state;
}

program_state
checkCorrect(problem p)
{
  if (p.hasTester) 
  {
    return compareOutput(p.file_test, p.file_stdin, p.file_stdout);
  } 
  else 
  {
    return compareOutput("diff -B", p.file_test, p.file_stdout);
  }
}
