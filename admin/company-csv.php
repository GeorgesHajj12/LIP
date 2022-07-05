<?php
include 'inc/config.php';
$now = gmdate("D, d M Y H:i:s");
header('Content-Type: text/csv; charset=utf-8');  
header('Content-Disposition: attachment; filename=company_list.csv');  
$output = fopen("php://output", "w");  
fputcsv($output, array('Id', 'Name','Email','City'));  
$statement = $pdo->prepare("SELECT * FROM company WHERE company_status_id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
foreach ($result as $row) {
	fputcsv($output, array($row['company_id'],$row['company_name'],$row['company_email'],$row['company_city']));
} 
fclose($output);
?>