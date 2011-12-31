#ifndef GLOBALS_H
#define GLOBALS_H

#include <stdio.h>
#include <stdlib.h>

// GLOBALS
#ifndef PATH_JOB_FOLDER
#define PATH_JOB_FOLDER "/var/www/geekrpg/library/judge/jobs"
#endif /* PATH_JOB_FOLDER */

#ifndef MAX_PATH_LENGTH
#define MAX_PATH_LENGTH 256
#endif /* MAX_PATH_LENGTH */

#ifndef PATH_PROBLEM_FOLDER
#define PATH_PROBLEM_FOLDER "/var/www/geekrpg/library/judge/problems"
#endif /* PATH_PROBLEM_FOLDER */

#ifndef TIME_WAIT_INTERVAL_MS
#define TIME_WAIT_INTERVAL_MS 1
#endif /* TIME_WAIT_INTERVAL_MS */

/**
 * States in which the execution of a program can end.
 */
typedef enum program_state {
  STATE_CORRECT,
  STATE_WRONG_ANSWER,
  STATE_NO_OUTPUT,
  STATE_COMPILE_ERROR,
  STATE_RUN_ERROR,
  STATE_RETURN_NOT_ZERO,
  STATE_TIME_LIMIT_EXCEEDED,
  STATE_MEMORY_LIMIT_EXCEEDED,
  STATE_OUTPUT_LIMIT_EXCEEDED,
  STATE_ILLEGAL_SYSCALL,
  STATE_INTERNAL_ERROR
} program_state;

/**
 * TODO: unimplemented
 */
typedef enum language {
  C,
  CPP,
  PHP
} language;

void 
logError(
  char* message, /* what happened */
  char* where    /* where did it happen */
);

void 
criticalFail(
  char* message /* description of failure */
);

#endif /* GLOBALS_H */
