
/* credit @Daniel Scocco */

/****************** CLIENT CODE ****************/

#include <stdio.h>
#include <sys/socket.h>
#include <netinet/in.h>
#include <string.h>
#include <arpa/inet.h>
#include <unistd.h>
#include <stdlib.h>

#define SHELLSCRIPT "\
firefox Output.html&"

int main(){
  int clientSocket;
  struct sockaddr_in serverAddr;
  socklen_t addr_size;
  char query[100];
  /*---- Create the socket. The three arguments are: ----*/
  /* 1) Internet domain 2) Stream socket 3) Default protocol (TCP in this case) */
  clientSocket = socket(PF_INET, SOCK_STREAM, 0);

  /*---- Configure settings of the server address struct ----*/
  /* Address family = Internet */
  serverAddr.sin_family = AF_INET;
  /* Set port number, using htons function to use proper byte order */
  serverAddr.sin_port = htons(4050);
  /* Set IP address to localhost */
  serverAddr.sin_addr.s_addr = inet_addr("0.0.0.0");
  /* Set all bits of the padding field to 0 */
  memset(serverAddr.sin_zero, '\0', sizeof serverAddr.sin_zero);

  /*---- Connect the socket to the server using the address struct ----*/
  addr_size = sizeof serverAddr;
  connect(clientSocket, (struct sockaddr *) &serverAddr, addr_size);

  /* Take the query as input*/
  scanf("%[^\n]", query);
  send(clientSocket, query, sizeof(query), 0);
  
  unsigned long fsize; 
  recv(clientSocket, &fsize, sizeof(uint64_t), 0);
  printf("%ld\n", fsize);

  char * buff;
  buff = (char*) malloc (sizeof(char)*fsize);
  recv(clientSocket, buff, sizeof(char)*fsize, 0);
  FILE *file = fopen("Output.html", "w");
  int results = fputs(buff, file);
  if (results == EOF) {
      printf("Failed to write do error code here.\n");
  }
  fclose(file);
  system(SHELLSCRIPT);
  return 0;
}
