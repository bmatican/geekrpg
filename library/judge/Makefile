CC=gcc
PROG=play
CFLAGS=-Wall -lrt -o $(PROG)

all: compile

run: compile
	./$(PROG) 300
	
compile: $(PROG).c
	$(CC) $(CFLAGS) *.c

clean:
	rm $(PROG)
