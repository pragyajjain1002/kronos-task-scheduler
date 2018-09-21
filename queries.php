<html>

<head>
<title>Information</title>
</head>

<body>
<?php
//php7 tested 
//replace db, user and password
$link = mysqli_connect("localhost", "root", "", "employees");
echo "Start".$_POST['last_name'];
// did we connected?
if (!$link) {
	echo "Error: Unable to connect to MySQL." . PHP_EOL;
	echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
	echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
	exit;
}
if(!empty($_POST['last_name'])){
	$query = "SELECT * FROM employees where last_name=\"".$_POST['last_name']."\"; ";
	echo "hye\n";
}
if(!empty($_POST['dept'])){
	$query = "SELECT * FROM employees where emp_no in (select emp_no from dept_emp where dept_no in (select dept_no from departments where dept_name=\"".$_POST['dept']."\"));";
	echo "hye\n";
}
if(!empty($_POST['id'])){
	$query = "SELECT * FROM employees where emp_no=".$_POST['id']."; ";
	echo "hye\n";
}
$result =$link->query($query);
echo "hye\n";
$output = '<table>';
foreach($result as $key => $var) {
	$output .= '<tr>';
	foreach($var as $k => $v) {
		if ($key === 0) {
			$output .= '<td><strong>' . $k . '</strong></td>';
		} else {
			$output .= '<td>' . $v . '</td>';
		}
	}
	$output .= '</tr>';
}
$output .= '</table>';
echo $output;

mysqli_close($link);
?>
</body>
</html> 
