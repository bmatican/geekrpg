#ifndef PROBLEM_H
#define PROBLEM_H

#include "globals.h"

#define PROBLEM_MAX_NAME        64
#define PROBLEM_MAX_EXTENSION   16

typedef struct problem {
  int jobid;
  
  char name[PROBLEM_MAX_NAME];
  char extension[PROBLEM_MAX_EXTENSION];
  
  char hasTester;
  int nrtests;
  
  int timeLimitMS;
  int heapLimitMB;
  int stackLimitMB;
  
  char file_source[MAX_PATH_LENGTH];
  char file_exec[MAX_PATH_LENGTH];
  char file_test[MAX_PATH_LENGTH];  /* path to tester or expectedOutput */
  char file_stdin[MAX_PATH_LENGTH];
  char file_stdout[MAX_PATH_LENGTH];
  char file_stderr[MAX_PATH_LENGTH];
} problem;

void 
problem_new (
  problem* p,       /* the problem to populate */
  int jobid,        /* the jobid */
  char* name,       /* the problem name */
  char* extension,  /* the extension for the job */
  int nrtests,      /* the number of tests */
  char hasTester    /* whether it has a tester associated or not */
);

void
problem_limits(
  problem* p,       /* the problem to populate */
  int timeLimitMS,  /* the time limit in milis */
  int heapLimitMB,  /* the heap limit in megabytes */
  int stackLimitMB  /* the stack limit in megabytes */
);

void
problem_test (
  problem* p, /* the problem to populate */
  int testNr  /* the number of the current test */
);

#endif /* PROBLEM_H */
