<html>
<link rel="stylesheet" type="text/css" href="result.css">

<?php
$connection = new MongoClient(); // connects to localhost:27017
echo "Success";

$db = $connection->test;

$result=$db->execute('return db.cases.aggregate([{$group : {_id : "$DISTRICT", num_cases : {$sum : 1}}}, {"$sort" : {"num_cases" : -1}}]).toArray()[0];');
?>
<h2>City with most crime</h2>
<table>
<tr>
    <th>CITY</th>
    <th>CASES</th>
</tr>
<tr>
    <td><?php echo $result['id']; ?></td>
    <td><? echo $result["num_cases"]; ?></td>
</tr>
</table>
<?

$result=$db->execute('return db.cases.aggregate([{$group : {_id : "$PS", num_cases : {$sum : 1}, pending_cases : {$sum : {"$cond": [{ "$eq": [ "$Status", "Pending"]},1,0]}}}}, {$project:{ratio :{ $divide: [ "$pending_cases", "$num_cases" ]}}}, { $sort: { "ratio": -1, "pending_cases": -1 } }]).toArray()[0];');
?>
<h2>City with least effective police</h2>
<table>
<tr>
    <th>CITY</th>
    <th>Ratio</th>
</tr>
<tr>
    <td><? echo $result["_id"]; ?></td>
    <td><? echo $result["ratio"]; ?></td>
</tr>
</table>
<?

$result=$db->execute('return db.cases.aggregate([{$unwind : "$Act_Section"}, {$group : {_id : "$Act_Section", num_cases : {$sum : 1}}}, {"$sort" : {"num_cases" : -1}},{$group: {_id:{},most:{$first:"$$ROOT"}, least:{$last:"$$ROOT"}}}])');
?>
<h2>Most used act</h2>
<table>
<tr>
    <th>Act</th>
    <th>Cases</th>
</tr>
<tr>
    <td><? echo $result["most"]["_id"]; ?></td>
    <td><? echo $result["least"]["id"]; ?></td>
</tr>
</table>
</html>
