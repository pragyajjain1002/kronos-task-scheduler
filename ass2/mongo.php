<html>
<?php
$connection = new MongoClient(); // connects to localhost:27017
echo "Success";

$db = $connection->test;
print_r($db->execute('return db.cases.aggregate([{$group : {_id : "$DISTRICT", num_cases : {$sum : 1}}}, {"$sort" : {"num_cases" : -1}}]).toArray()[0];'));

print_r($db->execute('return db.cases.aggregate([{$match : {"Status" : "Pending"}}, {$group : {_id : "$PS", num_cases : {$sum : 1}}}, {"$sort" : {"num_cases" : -1}}])]).toArray().toArray()[0];'));

print_r($db->execute('return db.cases.aggregate([{$unwind : "$Act_Section"}, {$group : {_id : {"act" : "$Act_Section"}, num_cases : {$sum : 1}}}, {"$sort" : {"num_cases" : 1}}].toArray()[0]'));

print_r($db->execute('return db.cases.aggregate([{$unwind : "$Act_Section"}, {$group : {_id : {"act" : "$Act_Section"}, num_cases : {$sum : 1}}}, {"$sort" : {"num_cases" : -1}}].toArray()[0]'));
?>
</html>
