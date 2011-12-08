#ifndef CONSTANTS_H
#define CONSTANTS_H

#include <stdio.h>
#include <stdlib.h>

// GLOBALS
#ifndef PATH_JOB_FOLDER
#define PATH_JOB_FOLDER "/var/www/geekrpg/library/judge/jobs"
#endif // PATH_JOB_FOLDER

#ifndef MAX_PATH_LENGTH
#define MAX_PATH_LENGTH 255
#endif // MAX_PATH_LENGTH

#ifndef PATH_PROBLEM_FOLDER
#define PATH_PROBLEM_FOLDER "/var/www/geekrpg/library/judge/problems"
#endif // PATH_PROBLEM_FOLDER

#ifndef TIME_WAIT_INTERVAL_MS
#define TIME_WAIT_INTERVAL_MS 1
#endif // TIME_WAIT_INTERVAL_MS

#ifndef HAS_BOOL
#define HAS_BOOL

/**
 * Faking CPP bools...could switch to CPP if we decide we need to...
 */
enum bool {
  false = 0,
  true  = 1
};
typedef enum bool bool;

#endif /* HAS_BOOL */

/**
 * States in which the execution of a program can end.
 */
enum program_state {
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
};
typedef enum program_state program_state;

/**
 * TODO: unimplemented
 */
enum language {
  C,
  CPP,
  PHP
};
typedef enum language language;

void logError(char* message, char* where) {
  char error[255];
  (void) sprintf(error, "%s %s\n", message, where);
  (void) fprintf(stderr, "%s", error);
  //perror(error);
}

void criticalFail(char* message) {
  perror(message);
  exit(EXIT_FAILURE);
}

#endif /* CONSTANTS_H */
