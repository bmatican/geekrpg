#include "globals.h"

void 
logError(
  char* message,
  char* where
) {
  char error[255];
  (void) sprintf(error, "%s %s\n", message, where);
  (void) fprintf(stderr, "%s", error);
}

void 
criticalFail(
  char* message
) {
  perror(message);
  exit(EXIT_FAILURE);
}
