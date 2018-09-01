---


---

<h1 id="assignment-1">Assignment 1</h1>
<h2 id="work-distribution">Work distribution</h2>

<table>
<thead>
<tr>
<th>Abhay  and Siraj</th>
<th>Docker</th>
</tr>
</thead>
<tbody>
<tr>
<td>Akash and Pragya</td>
<td>Socket</td>
</tr>
</tbody>
</table><p>Instructions to build this application</p>
<pre><code>Docker build using Dockerfile using EXPOSE port
// ADD /Assignment1 $HOME/cs252_ass1
// EXPOSE 5433
link to dockerhub - https://hub.docker.com/r/kineau/cs252_ass1/
Run server on docker

// To run client side code, compile client.c and then run the compiled file
gcc -o client client.c
./client

</code></pre>
<h3 id="input-format">Input format</h3>
<p>Number of images of each type<br>
Example inputs:</p>
<pre><code>2 dogs and 3 cats
2 cats
2 trucks 3 dogs and 2 cars
</code></pre>
<p>For this application we had taken 4 images of each type i.e(4 of cars, trucks, cats and dogs each)<br>
On execution of client side code a html file will be created and will be automatically launched in firefox.</p>
<p>script. sh is for compressing images<br>
script2. sh is for creating base64 of images<br>
Note: Change path in scripts if you want to change the image set</p>


