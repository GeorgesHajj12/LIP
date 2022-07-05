<?php require_once('header.php'); ?>

<section class="content-header">
	<h1>Dashboard</h1>
</section>

<?php


$statement = $pdo->prepare("SELECT * FROM products WHERE company_id=?");
$statement->execute(array($_SESSION['company']['company_id']));
$total_product = $statement->rowCount();





$statement = $pdo->prepare("SELECT * 
                            FROM tbl_payment t1
                            JOIN tbl_order t2
                            ON t1.payment_id = t2.payment_id
                            WHERE payment_status=? AND company_id=?");
$statement->execute(array('Completed',$_SESSION['company']['company_id']));
$total_order_completed = $statement->rowCount();



$statement = $pdo->prepare("SELECT * 
                            FROM tbl_payment t1
                            JOIN tbl_order t2
                            ON t1.payment_id = t2.payment_id
                           WHERE payment_status=? AND company_id=?");
$statement->execute(array('Pending',$_SESSION['company']['company_id']));
$total_order_pending = $statement->rowCount();


?>

<section class="content">
<div class="row">
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-primary">
                <div class="inner">
                  <h3><?php echo $total_product; ?></h3>

                  <p>Products</p>
                </div>
                <div class="icon">
                  <i class="ionicons ion-android-cart"></i>
                </div>
                
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-maroon">
                <div class="inner">
                  <h3><?php echo $total_order_pending; ?></h3>

                  <p>Pending Orders</p>
                </div>
                <div class="icon">
                  <i class="ionicons ion-clipboard"></i>
                </div>
                
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-green">
                <div class="inner">
                  <h3><?php echo $total_order_completed; ?></h3>

                  <p>Completed Orders</p>
                </div>
                <div class="icon">
                  <i class="ionicons ion-android-checkbox-outline"></i>
                </div>
               
              </div>
            </div>
           

			 
</section>

<?php require_once('footer.php'); ?>