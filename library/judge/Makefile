CC=gcc
PROG=play
CFLAGS=-Wall -lrt -o $(PROG)

all: compile

run: compile
	./$(PROG) 300
	
compile: $(PROG).c
	$(CC) $(CFLAGS) $(PROG).c

clean:
	rm $(PROG)
