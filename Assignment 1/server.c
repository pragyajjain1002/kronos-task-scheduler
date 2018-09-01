/* credit @Daniel Scocco */

/****************** SERVER CODE ****************/

#include <stdio.h>
#include <netinet/in.h>
#include <string.h>
#include <sys/socket.h>
#include <arpa/inet.h>
#include <stdlib.h>
#include <unistd.h>

int main(){
  int welcomeSocket, newSocket;
  struct sockaddr_in serverAddr;
  struct sockaddr_storage serverStorage;
  socklen_t addr_size;

  /*---- Create the socket. The three arguments are: ----*/
  /* 1) Internet domain 2) Stream socket 3) Default protocol (TCP in this case) */
  welcomeSocket = socket(PF_INET, SOCK_STREAM, 0);

  /*---- Configure settings of the server address struct ----*/
  /* Address family = Internet */
  serverAddr.sin_family = AF_INET;
  /* Set port number, using htons function to use proper byte order */
  serverAddr.sin_port = htons(5432);
  /* Set IP address to localhost */
  serverAddr.sin_addr.s_addr = inet_addr("127.0.0.1");
  /* Set all bits of the padding field to 0 */
  memset(serverAddr.sin_zero, '\0', sizeof serverAddr.sin_zero);

  /*---- Bind the address struct to the socket ----*/
  bind(welcomeSocket, (struct sockaddr *) &serverAddr, sizeof(serverAddr));

  /*---- Listen on the socket, with 5 max connection requests queued ----*/
  if(listen(welcomeSocket,5)==0)
    printf("I'm listening\n");
  else
    printf("Error\n");

  /*---- Accept call creates a new socket for the incoming connection ----*/
  addr_size = sizeof serverStorage;
  newSocket = accept(welcomeSocket, (struct sockaddr *) &serverStorage, &addr_size);

  /*---- Receiving query from the socket of the incoming connection ----*/
  char query[100];
  recv(newSocket, query, sizeof(query), 0);
  
  int cats = 0, dogs = 0, cars = 0, trucks = 0;

  char * parsedQuery;
  parsedQuery = strtok (query," ");
  int n;

  /* Parsing the query */
  while (parsedQuery != NULL)
  {
    if(strcmp(parsedQuery, "and"))
    {
	n = atoi(parsedQuery);
       parsedQuery = strtok (NULL, " ");

	if(!strcmp(parsedQuery, "cats"))
		cats = n;
	else if(!strcmp(parsedQuery, "dogs"))
		dogs = n;
	else if(!strcmp(parsedQuery, "cars"))
		cars = n;
	else 
		trucks = n;
		
    }
    parsedQuery = strtok (NULL, " ");
  }

  int imageCount[4] = {cats, dogs, cars, trucks};
  char fileCategory[4][6] = {"cat", "dog", "car", "truck"};

  for(int i=0;i<4;i++)
  {
	send(newSocket, &imageCount[i], sizeof(int), 0);
  }

  FILE *picture;
  unsigned long fsize;

  for(int i = 0; i < 4; i++)
  {
  	for(int j=0; j<imageCount[i]; j++)
  	{
		char fileName[30] = "";
		sprintf(fileName, "./images/%s%d.png", fileCategory[i],j+1);
	  	picture = fopen(fileName, "r");
  		if (picture == NULL) 
  		{
      			printf("File not found!\n");
      			return 1;
  		}
 	 	else 
		{
    			fseek(picture, 0, SEEK_END);
   	 		fsize = ftell(picture);
    			fseek(picture, 0, SEEK_SET);
		}
		//Picture Size
		send(newSocket, &fsize, sizeof(uint64_t), 0);

		//Send Picture as Byte Array
		char buffer[fsize];
		int result = fread (buffer,1,fsize,picture);
		if (result != fsize) {fputs ("Reading error",stderr); exit (3);}
		send(newSocket, buffer, sizeof(buffer), 0);

         	fclose(picture);  
  	}
  } 
  return 0;
}
