#include <string.h>

#include "problem.h"
#include "path.h"

void 
problem_new(
  problem* p,
  int jobid,
  char* name,
  char* extension,
  int nrtests,
  char hasTester
) {
  // naming
  p->jobid = jobid;
  strncpy(p->name, name, PROBLEM_MAX_NAME);
  strncpy(p->extension, extension, PROBLEM_MAX_EXTENSION);
  
  // tests
  p->hasTester = hasTester;
  p->nrtests = nrtests;
  
  // paths
  pathJobExecutable(p->file_exec, jobid);
  pathJobSource(p->file_source, jobid, extension);
  strcpy(p->file_stderr, "/dev/null");
  if (hasTester) {
    pathProblemTester(p->file_test, p->name); 
  }
}

void
problem_limits(
  problem* p, 
  int timeLimitMS, 
  int heapLimitMB, 
  int stackLimitMB
) {
  p->timeLimitMS = timeLimitMS;
  p->heapLimitMB = heapLimitMB;
  p->stackLimitMB = stackLimitMB;
}

void
problem_test(
  problem* p, 
  int testNr
) {
  pathProblemInput(p->file_stdin, p->name, testNr);
  pathJobOutput(p->file_stdout, p->jobid, testNr);
  if (!p->hasTester) 
  {
    pathProblemOutput(p->file_test, p->name, testNr);
  }
}
