#ifndef JUDGE_H
#define JUDGE_H

#include "globals.h"
#include "problem.h"

program_state
compile(
  problem p   /* the problem to compile */
);

program_state
execute(
  problem p   /* the problem to compile */
);

program_state
judge(
  int jobid   /* the jobid of the current execution */
);

#endif /* JUDGE_H */
