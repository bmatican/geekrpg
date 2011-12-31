#ifndef WAIT_H
#define WAIT_H

#include <unistd.h>

#include "globals.h"
#include "problem.h"

program_state
waitChild(
  pid_t pid /* the pid to wait for */
);

program_state
timedWaitChild(
  pid_t child, /* the child to wait for */
  problem p    /* the problem description */
);


#endif /* WAIT_H */
