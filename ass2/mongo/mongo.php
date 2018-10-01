<html>
<link rel="stylesheet" type="text/css" href="result.css">

<?php
$connection = new MongoClient(); // connects to localhost:27017
// echo "Success";

$db = $connection->cs252;
$collection = $connection->cs252->cases;


$result = $collection->aggregateCursor( [
                [ '$group' => [ '_id' => '$DISTRICT', 'Num_cases' => [ '$sum' => 1 ] ] ],
                [ '$sort' => [ 'Num_cases' => -1 ] ],
          [ '$limit' => 1 ],
                ] );

echo "<h2>City with most crime</h2>
<table>
<tr>
    <th>CITY</th>
    <th>CASES</th>
</tr>";

foreach($result as $var)
{
  echo"<tr>";
      echo "<td>".$var['_id']."</td>";
      echo "<td>".$var['Num_cases']."</td>";
  echo "</tr>";
}
echo "</table>";

echo "<h2>City with least effective police</h2>";
$result = $collection->aggregateCursor( [
            [ '$group' => [ '_id' => '$PS', 'Num_cases' => [ '$sum' => 1 ],'Pending_cases' => [ '$sum' => [ '$cond' => [ [ '$eq'=> [ '$Status','Pending']], 1, 0] ] ]
 ] ],
            [ '$project' => [ 'ratio' => [ '$divide' => [ '$Pending_cases','$Num_cases' ]], 'Pending_cases'=>1]],
            [ '$sort' => [ 'ratio' => -1, 'Pending_cases' => -1  ]],
            [ '$limit' => 1 ],      
          ]);
          
echo "<table>
<tr>
    <th>CITY</th>
    <th>Ratio</th>
    <th>Pending_cases</th>
</tr>";

foreach($result as $var)
{
  echo"<tr>";
      echo "<td>".$var['_id']."</td>";
      echo "<td>".$var['ratio']."</td>";
      echo "<td>".$var['Pending_cases']."</td>";
  echo "</tr>";
}

echo "</table>";

$result = $collection->aggregateCursor( [
       ['$unwind' => '$Act_Section'], 
       [ '$group' => [ '_id' => '$Act_Section', 'Num_cases' => [ '$sum' => 1]]],
       [ '$sort' => [ 'Num_cases' => -1]],
       [ '$limit' => 1]
    ]);

echo "<h2>Most used act</h2>";
echo "<table>
<tr>
    <th>Act_Section</th>
    <th>Cases</th>
</tr>";

foreach($result as $var)
{
  echo"<tr>";
      echo "<td>".$var['_id']."</td>";
      echo "<td>".$var['Num_cases']."</td>";
  echo "</tr>";
}
echo "</table>";

$result = $collection->aggregateCursor( [
       ['$unwind' => '$Act_Section'], 
       [ '$group' => [ '_id' => '$Act_Section', 'Num_cases' => [ '$sum' => 1]]],
       [ '$sort' => [ 'Num_cases' => 1]],
    ]);

echo "<h2>Least used act</h2>";
echo "<table>
<tr>
    <th>Act_Section</th>
    <th>Cases</th>
</tr>";

foreach($result as $var)
{
  if($var['Num_cases']==1){
      echo"<tr>";
      echo "<td>".$var['_id']."</td>";
      echo "<td>".$var['Num_cases']."</td>";
      echo "</tr>";
  }
}
echo "</table>";
?>
</html>
