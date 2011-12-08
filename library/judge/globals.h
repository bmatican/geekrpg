#ifndef GLOBALS_H
#define GLOBALS_H

// PROGRAM STATES
program_state {
  STATE_CORRECT,
  STATE_WRONG_ANSWER,
  STATE_NO_OUTPUT,
  STATE_COMPILE_ERROR,
  STATE_RUN_ERROR,
  STATE_RETURN_NOT_ZERO,
  STATE_TIME_LIMIT_EXCEEDED,
  STATE_INTERNAL_ERROR
};

enum language {
  C,
  CPP,
  PHP
};

void logError(char* message, char* where) {
  char error[255];
  (void) sprintf(error, "%s %s", message, where);
  perror(error);
}

#endif // GLOBALS_H
