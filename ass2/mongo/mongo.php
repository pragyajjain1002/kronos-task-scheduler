<html>
<?php
$connection = new MongoClient(); // connects to localhost:27017
echo "Success";

$db = $connection->test;
echo "Query 1";
print_r($db->execute('return db.cases.aggregate([{$group : {_id : "$DISTRICT", num_cases : {$sum : 1}}}, {"$sort" : {"num_cases" : -1}}]).toArray()[0];'));
echo "Query 2";
print_r($db->execute('return db.cases.aggregate([{$group : {_id : "$PS", num_cases : {$sum : 1}, pending_cases : {$sum : {"$cond": [{ "$eq": [ "$Status", "Pending"]},1,0]}}}}, {$project:{ratio :{ $divide: [ "$pending_cases", "$num_cases" ]}}}, { $sort: { "ratio": -1, "pending_cases": -1 } }]).toArray()[0];'));
echo "Query 3";
print_r($db->execute('return db.cases.aggregate([{$unwind : "$Act_Section"}, {$group : {_id : "$Act_Section", num_cases : {$sum : 1}}}, {"$sort" : {"num_cases" : -1}},{$group: {_id:{},most:{$first:"$$ROOT"}, least:{$last:"$$ROOT"}}}])'));
?>
</html>
