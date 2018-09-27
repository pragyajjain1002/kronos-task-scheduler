<html>

<head>
<title>Information</title>
</head>

<body>
<?php
//php7 tested 
//replace db, user and password

$link = mysqli_connect("localhost", "root", "", "employees") or die('not connecting');

// did we connected?
if (!$link) {
	echo "Error: Unable to connect to MySQL." . PHP_EOL;
	echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
	echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
	exit;
}
if(!empty($_POST['last_name'])){
	$query = "SELECT * FROM employees where last_name=\"".$_POST['last_name']."\"; ";
}
if(!empty($_POST['dept'])){
	$query = "SELECT * FROM employees where emp_no in (select emp_no from dept_emp where dept_no in (select dept_no from departments where dept_name=\"".$_POST['dept']."\"));";
}
if(!empty($_POST['id'])){
	$query = "SELECT * FROM employees where emp_no=".$_POST['id']."; ";
}
if(!empty($_POST['q2'])){
	$query="select departments.dept_no, departments.dept_name from departments inner join (select dept_no, COUNT(dept_no) from dept_emp group by dept_no order by COUNT(dept_no) DESC LIMIT 1)temp on departments.dept_no = temp.dept_no;";
}
if(!empty($_POST['q3'])){
	$query="select first_name, last_name, emp_no, dept_no, Tenure from (select employees.first_name, employees.last_name, dept_emp.emp_no, dept_emp.dept_no, datediff(dept_emp.to_date, dept_emp.from_date) as \"Tenure\" from employees inner join dept_emp on employees.emp_no = dept_emp.emp_no)temp where dept_no = \"".$_POST['dept_no']."\" order by dept_no, Tenure;";
}
if(!empty($_POST['q4'])){
$query="select dept_no, COUNT(CASE WHEN GENDER=\"F\" THEN 1 END)/COUNT(CASE WHEN GENDER=\"M\" THEN 1 END) as \"Gender Ratio\" from (select dept_emp.dept_no, employees.gender from employees inner join dept_emp on employees.emp_no = dept_emp.emp_no)temp where dept_no=\"".$_POST["dept_no"]."\" order by dept_no;";
}
if(!empty($_POST['q5'])){
$query="select dept_no, SUM(case when gender=\"F\" then salary end)/SUM(case when gender=\"M\" then salary end) as Income_Ratio from (select dept_emp.dept_no, employees.gender, salaries.salary from dept_emp inner join employees on employees.emp_no = dept_emp.emp_no inner join salaries on salaries.emp_no = employees.emp_no)temp where dept_no=\"".$_POST["dept_no"]."\" order by dept_no";
}
$result =$link->query($query);
$output = '<table>';
$temp='';
foreach($result as $key => $var) {
	$output .= '<tr>';
	foreach($var as $k => $v) {
		if ($key === 0) {
			$output .= '<td><strong>' . $k . '</strong></td>';
		}
		$temp .= '<td>' . $v . '</td>';
		
	}
	if ($key === 0) {
		$output .= '</tr>';
	}
	$output .=$temp;
	$temp='';
	$output .= '</tr>';
}
$output .= '</table>';
echo $output;

mysqli_close($link);
?>
</body>
</html> 
