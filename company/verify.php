<?php require_once('header.php'); ?>

<?php
if ( (!isset($_REQUEST['company_email'])) || (isset($_REQUEST['company_token'])) )
{
    $var = 1;

    // check if the token is correct and match with database.
    $statement = $pdo->prepare("SELECT * FROM company WHERE company_email=?");
    $statement->execute(array($_REQUEST['company_email']));
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);                           
    foreach ($result as $row) {
        if($_REQUEST['token'] != $row['company_token']) {
            header('location:');
            exit;
        }
    }

    // everything is correct. now activate the company removing token value from database.
    if($var != 0)
    {
        $statement = $pdo->prepare("UPDATE company SET company_token=?, company_status=? WHERE company_email=?");
        $statement->execute(array('',1,$_GET['company_email']));

        $success_message = '<p style="color:green;">Your email is verified successfully. You can now login to our website.</p><p><a href="login.php" style="color:#167ac6;font-weight:bold;">Click here to login</a></p>';     
    }
}
?>

<div class="page-banner" style="background-color:#444;">
    <div class="inner">
        <h1>Registration Successful</h1>
    </div>
</div>

<div class="page">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="user-content">
                    <?php 
                        echo $error_message;
                        echo $success_message;
                    ?>
                </div>                
            </div>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>