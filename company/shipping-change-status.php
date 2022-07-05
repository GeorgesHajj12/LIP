<?php require_once('header.php'); ?>

<?php
include("../New folder (5)/sendEmail.php");
if( !isset($_REQUEST['id']) || !isset($_REQUEST['task']) ) {
	header('location: logout.php');
	exit;
} else {
	// Check the id is valid or not
	$statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE pay_id=?");
	$statement->execute(array($_REQUEST['id']));
	$total = $statement->rowCount();
	if( $total == 0 ) {
		header('location: logout.php');
		exit;
	}
}
?>

<?php
	$statement = $pdo->prepare("UPDATE tbl_payment SET shipping_status=? WHERE pay_id=?");
	$statement->execute(array($_REQUEST['task'],$_REQUEST['id']));
	$statement1 = $pdo->prepare("SELECT customer_FirstName,customer_email FROM tbl_payment WHERE shipping_status=? and pay_id=?");
	$statement1->execute(array($_REQUEST['task'],$_REQUEST['id']));
	$result = $statement1->fetchAll(PDO::FETCH_ASSOC);                            
	foreach ($result as $row) {
		$cust_email = $row['customer_email'];
		$cust_name= $row['customer_FirstName'];
	}
	$mybody='Your shipping is now completed';
	 sendEmailKK($cust_name,$cust_email,$mybody);
	header('location: order.php');
?>