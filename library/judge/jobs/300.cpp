#include <stdio.h>
#include <unistd.h>
#include <stdlib.h>
#include <string.h>

using namespace std;

int main() {
  /*
  long MAXIM = 1 * 1024 * 1024;
  char * c = (char*) malloc(MAXIM);
  
  memset(c, 0, MAXIM);

  int i;
  for (i = 0; i < MAXIM; ++i) {
    c[i] = 1;
  }

  free(c);
  */
  //usleep(10000000);
  printf("hahaha, I WORK!\n");
  fflush(stdout);
  while(1) ;
  fprintf(stderr, "someerror\n");
  return 0;
}
