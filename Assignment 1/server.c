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
  static const char CSS[] = "<style>\n body{\n overflow-x:hidden;\n font-family:arial;\n background-image:linear-gradient(rgb(250,250,250),rgb(200,200,200));\n text-transform:uppercase;\ndisplay:flex;\nalign-items: center;\nwidth: 100vw;min-height:100vh;\njustify-content: center;\nflex-direction: column;\nmargin:50px ;\n}\n div{\n max-width:60%;overflow-x:scroll; \n}\nimg{\nborder-radius: 5%;\n-webkit-box-shadow: 3px 3px 5px 6px #ccc;\n-moz-box-shadow:3px 3px 5px 6px #ccc;\nbox-shadow:3px 3px 5px 6px #ccc;}</style>";
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
  serverAddr.sin_port = htons(4050);
  /* Set IP address to localhost */
  serverAddr.sin_addr.s_addr = inet_addr("0.0.0.0");
  /* Set all bits of the padding field to 0 */
  int opt=1;
  memset(serverAddr.sin_zero, '\0', sizeof serverAddr.sin_zero);
  if (setsockopt(welcomeSocket, SOL_SOCKET, SO_REUSEADDR | SO_REUSEPORT,
        &opt, sizeof(opt)))
  {
    perror("setsockopt");
    exit(EXIT_FAILURE);
  }
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
  printf("%s\n", query);  
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

      if(!strcmp(parsedQuery, "cats")||!strcmp(parsedQuery, "cat"))
        cats = n;
      else if(!strcmp(parsedQuery, "dog")||!strcmp(parsedQuery, "dogs"))
        dogs = n;
      else if(!strcmp(parsedQuery, "car")||!strcmp(parsedQuery, "cars"))
        cars = n;
      else  if(!strcmp(parsedQuery, "truck")||!strcmp(parsedQuery, "trucks"))
        trucks = n;
    }
    parsedQuery = strtok (NULL, " ");
  }
  printf("%d %d %d %d\n", cats, dogs, cars, trucks);
  int imageCount[4] = {cats, dogs, cars, trucks};
  char fileCategory[4][6] = {"cat", "dog", "car", "truck"};
  char html[1024*1024];
  sprintf(html, "<body><h1>Welcome</h1>\n");
  sprintf(html, "%s %s\n", html,CSS);
  for(int i=0;i<4;i++)
  {
    if(imageCount[i])
      sprintf(html,"%s<h2>%s</h2><div><table><tr>",html, fileCategory[i]);
    for(int j=0; j<imageCount[i]; j++){
      char temp[25];
      sprintf(temp,"./cs252_ass1/images/img/%s%d.png.dat",fileCategory[i],j+1);
      int c;
      char buff[40*1024];
      int l=0;
      FILE *file;
      file = fopen(temp, "r");
      if (file) {
        while ((c = getc(file)) != EOF){
          buff[l]=(char) c;
          l++;
        }
        buff[l]='\0';
        fclose(file);
      }
      sprintf(html, "%s<td><img src='data:image/png;base64, %s'width='250' height='200' alt='Red dot'></img></td>\n",html, buff);
    } 
    sprintf(html, "%s</tr></table></div>\n", html);
  } 
  sprintf(html, "%s</body>", html);
  unsigned long fsize = strlen(html);
  send(newSocket, &fsize, sizeof(uint64_t), 0);
  send(newSocket, html, sizeof(char)*fsize, 0);
  int true = 1;
  setsockopt(newSocket,SOL_SOCKET,SO_REUSEADDR,&true,sizeof(int));
  return 0;
}
