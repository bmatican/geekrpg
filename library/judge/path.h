#ifndef PATH_H
#define PATH_H

void 
pathJobExecutable(
  char* executable, 
  int jobid
);

void 
pathJobSource(
  char* source, 
  int jobid, 
  char* language
);

void 
pathJobOutput(
  char* output, 
  int jobid, 
  int testNr
);

void 
pathProblemInput(
  char* input, 
  char* problemname, 
  int testNr
);

void 
pathProblemOutput(
  char* actualOutput, 
  char* problemname, 
  int testNr
);

void 
pathProblemTester(
  char* tester, 
  char* problemname
);

#endif /* PATH_H */
