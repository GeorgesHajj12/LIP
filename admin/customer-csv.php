<?php
include 'inc/config.php';
$now = gmdate("D, d M Y H:i:s");
header('Content-Type: text/csv; charset=utf-8');  
header('Content-Disposition: attachment; filename=customer_list.csv');  
$output = fopen("php://output", "w");  
fputcsv($output, array('Id', 'Name','Email','Country id','City','State'));  
$statement = $pdo->prepare("SELECT * FROM customer WHERE cust_status=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
foreach ($result as $row) {
	fputcsv($output, array($row['cust_id'],$row['cust_FirstName'].' '.$row['cust_LastName'],$row['cust_email'],$row['cust_country'],$row['cust_city'],$row['cust_state']));
} 
fclose($output);
?>