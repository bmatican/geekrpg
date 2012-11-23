#include <stdio.h>
#include <unistd.h>
#include <stdlib.h>
#include <string.h>

#include <sys/stat.h>
#include <sys/types.h>

using namespace std;

int main() {
  long MAXIM = 1 * 1024 * 1024;
  long FULL = 100;
  char * c[FULL];
  
  for (int j = 0; j < FULL; ++j) {
    fprintf(stderr, "memory batch %d\n", j);
    c[j] = (char*)malloc(MAXIM * sizeof(char));
    if (NULL == c[j]) {
      exit(EXIT_FAILURE);
    }
    memset(c[j], 0, MAXIM);

    for (int i = 0; i < MAXIM; ++i) {
      c[j][i] = 1;
    }
  }
  //usleep(10000000);
  printf("hahaha, I WORK!\n");
  fflush(stdout);
  //while(1) ;
  fprintf(stderr, "someerror\n");
  /*
  printf("hahaha, I WORK!\n");
  mkdir("gigi", 0777);
  chroot("gigi");
  chdir("../");
  FILE *f = fopen("escape", "w");
  fprintf(f, "Hello from the jail!\n");
  fclose(f);
  //*/
  return 0;
}
