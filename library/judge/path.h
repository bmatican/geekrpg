#ifndef PATH_H
#define PATH_H

#include <stdio.h>

#include "constants.h"

void pathJobExecutable(char* executable, int jobid) {
  (void) sprintf(executable, "%s/%d", PATH_JOB_FOLDER, jobid);
}

void pathJobSource(char* source, int jobid, char* language) {
  (void) sprintf(source, "%s/%d.%s", PATH_JOB_FOLDER, jobid, language);
}

void pathJobOutput(char* output, int jobid, int testNr) {
  (void) sprintf(output, "%s/%d_%d.out", PATH_JOB_FOLDER, jobid, testNr);
}

void pathProblemInput(char* input, char* problemname, int testNr) {
  (void) sprintf(input, "%s/%s_%d.in", PATH_PROBLEM_FOLDER, problemname, testNr);
}

void pathProblemOutput(char* actualOutput, char* problemname, int testNr) {
  (void) sprintf(actualOutput, "%s/%s_%d.out", PATH_PROBLEM_FOLDER, problemname, testNr);
}

void pathProblemTester(char* tester, char* problemname) {
  (void) sprintf(tester, "%s/%s.test", PATH_PROBLEM_FOLDER, problemname);
}

#endif /* PATH_H */
