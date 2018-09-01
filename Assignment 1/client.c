
/* credit @Daniel Scocco */

/****************** CLIENT CODE ****************/

#include <stdio.h>
#include <sys/socket.h>
#include <netinet/in.h>
#include <string.h>
#include <arpa/inet.h>
#include <unistd.h>
#include <stdlib.h>

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
  serverAddr.sin_port = htons(5432);
  /* Set IP address to localhost */
  serverAddr.sin_addr.s_addr = inet_addr("127.0.0.1");
  /* Set all bits of the padding field to 0 */
  memset(serverAddr.sin_zero, '\0', sizeof serverAddr.sin_zero);

  /*---- Connect the socket to the server using the address struct ----*/
  addr_size = sizeof serverAddr;
  connect(clientSocket, (struct sockaddr *) &serverAddr, addr_size);

  /* Take the query as input*/
  scanf("%s", query);
  send(clientSocket, query, sizeof(query), 0);
  char type[4][25]={"Cat", "Dog", "Car", "Truck"};
  printf("<body>\n");
  printf("<link rel='stylesheet' href='styles.css'>\n");
  for(int i=0;i<4;i++)
  {
    printf("<h2>%s</h2><table><tr>",type[i]);
    int item;
    recv(clientSocket, &item, sizeof(int), 0);
    unsigned long fsize;
    for(int j=0; j<item; j++){
      recv(clientSocket, &fsize, sizeof(uint64_t), 0);
      char buffer[fsize];
      recv(clientSocket, buffer, sizeof(char)*fsize, 0);
      char temp[25];
      sprintf(temp,"./images/%s%d.png",type[i],j);
      FILE* pOutput = fopen(temp,"wb");
      fprintf(pOutput,"%s", buffer);
      printf("<td><img src='%s'width='350' height='250'></img></td>\n",temp);
    }
    printf("</tr></table>\n");
  }
  printf("</body>");
  return 0;
}
