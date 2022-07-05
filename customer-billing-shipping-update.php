<?php require_once('header.php'); ?>

<?php
// Check if the customer is logged in or not
if(!isset($_SESSION['customer'])) {
    header('location:logout.php');
    exit;
} else {
    // If customer is logged in, but admin make him inactive, then force logout this user.
    $statement = $pdo->prepare("SELECT * FROM customer WHERE cust_id=? AND cust_status=?");
    $statement->execute(array($_SESSION['customer']['cust_id'],0));
    $total = $statement->rowCount();
    if($total) {
        header('location:logout.php');
        exit;
    }
}
?>

<?php
if (isset($_POST['form1'])) {


    // update data into the database
    $statement = $pdo->prepare("UPDATE customer SET 
                            cust_bil_FirstName=?, 
                            cust_bil_LastName=?, 
                            cust_bil_phone=?, 
                            cust_bil_country=?, 
                            cust_bil_address=?, 
                            cust_bil_city=?, 
                            cust_bil_state=?, 
                            cust_bil_zip=?,
                            cust_shi_FirstName=?, 
                            cust_shi_LastName=?, 
                            cust_shi_phone=?, 
                            cust_shi_country=?, 
                            cust_shi_address=?, 
                            cust_shi_city=?, 
                            cust_shi_state=?, 
                            cust_shi_zip=? 

                            WHERE cust_id=?");
    $statement->execute(array(
                            strip_tags($_POST['cust_bil_FirstName']),
                            strip_tags($_POST['cust_bil_LastName']),
                            strip_tags($_POST['cust_bil_phone']),
                            strip_tags($_POST['cust_bil_country']),
                            strip_tags($_POST['cust_bil_address']),
                            strip_tags($_POST['cust_bil_city']),
                            strip_tags($_POST['cust_bil_state']),
                            strip_tags($_POST['cust_bil_zip']),
                            strip_tags($_POST['cust_shi_FirstName']),
                            strip_tags($_POST['cust_shi_LastName']),
                            strip_tags($_POST['cust_shi_phone']),
                            strip_tags($_POST['cust_shi_country']),
                            strip_tags($_POST['cust_shi_address']),
                            strip_tags($_POST['cust_shi_city']),
                            strip_tags($_POST['cust_shi_state']),
                            strip_tags($_POST['cust_shi_zip']),
                            $_SESSION['customer']['cust_id']
                        ));  
   
    $success_message = 'Billing and Shipping Information is updated successfully.';

    $_SESSION['customer']['cust_bil_FirstName'] = strip_tags($_POST['cust_bil_FirstName']);
    $_SESSION['customer']['cust_bil_LastName'] = strip_tags($_POST['cust_bil_LastName']);
    $_SESSION['customer']['cust_bil_phone'] = strip_tags($_POST['cust_bil_phone']);
    $_SESSION['customer']['cust_bil_country'] = strip_tags($_POST['cust_bil_country']);
    $_SESSION['customer']['cust_bil_address'] = strip_tags($_POST['cust_bil_address']);
    $_SESSION['customer']['cust_bil_city'] = strip_tags($_POST['cust_bil_city']);
    $_SESSION['customer']['cust_bil_state'] = strip_tags($_POST['cust_bil_state']);
    $_SESSION['customer']['cust_bil_zip'] = strip_tags($_POST['cust_bil_zip']);
    $_SESSION['customer']['cust_shi_FirstName'] = strip_tags($_POST['cust_shi_FirstName']);
    $_SESSION['customer']['cust_shi_LastName'] = strip_tags($_POST['cust_shi_LastName']);
    $_SESSION['customer']['cust_shi_phone'] = strip_tags($_POST['cust_shi_phone']);
    $_SESSION['customer']['cust_shi_country'] = strip_tags($_POST['cust_shi_country']);
    $_SESSION['customer']['cust_shi_address'] = strip_tags($_POST['cust_shi_address']);
    $_SESSION['customer']['cust_shi_city'] = strip_tags($_POST['cust_shi_city']);
    $_SESSION['customer']['cust_shi_state'] = strip_tags($_POST['cust_shi_state']);
    $_SESSION['customer']['cust_shi_zip'] = strip_tags($_POST['cust_shi_zip']);

}
?>

<div class="page">
    <div class="container">
        <div class="row">            
            <div class="col-md-12"> 
                <?php require_once('customer_sidebar.php'); ?>
            </div>
            <div class="col-md-12">
                <div class="user-content">
                    <?php
                    if($error_message != '') {
                        echo "<div class='error' style='padding: 10px;background:#f1f1f1;margin-bottom:20px;'>".$error_message."</div>";
                    }
                    if($success_message != '') {
                        echo "<div class='success' style='padding: 10px;background:#f1f1f1;margin-bottom:20px;'>".$success_message."</div>";
                    }
                    ?>
                    <form action="" method="post">
                        <?php $csrf->echoInputField(); ?>
                        <div class="row">
                            <div class="col-md-6">
                                <h3><?php echo 'Update Billing Address'; ?></h3>
                                <div class="form-group">
                                    <label for=""><?php echo 'First Name'; ?></label>
                                    <input type="text" class="form-control" name="cust_bil_FirstName" value="<?php echo $_SESSION['customer']['cust_bil_FirstName']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for=""><?php echo 'Last Name'; ?></label>
                                    <input type="text" class="form-control" name="cust_bil_LastName" value="<?php echo $_SESSION['customer']['cust_bil_LastName']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for=""><?php echo 'Phone Number'; ?></label>
                                    <input type="text" class="form-control" name="cust_bil_phone" value="<?php echo $_SESSION['customer']['cust_bil_phone']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for=""><?php echo 'Country'; ?></label>
                                    <select name="cust_bil_country" class="form-control">
                                        <?php
                                        $statement = $pdo->prepare("SELECT * FROM tbl_country ORDER BY country_name ASC");
                                        $statement->execute();
                                        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($result as $row) {
                                            ?>
                                            <option value="<?php echo $row['country_id']; ?>" <?php if($row['country_id'] == $_SESSION['customer']['cust_bil_country']) {echo 'selected';} ?>><?php echo $row['country_name']; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for=""><?php echo 'Address'; ?></label>
                                    <textarea name="cust_bil_address" class="form-control" cols="30" rows="10" style="height:100px;"><?php echo $_SESSION['customer']['cust_bil_address']; ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for=""><?php echo 'City'; ?></label>
                                    <input type="text" class="form-control" name="cust_bil_city" value="<?php echo $_SESSION['customer']['cust_bil_city']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for=""><?php echo 'State'; ?></label>
                                    <input type="text" class="form-control" name="cust_bil_state" value="<?php echo $_SESSION['customer']['cust_bil_state']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for=""><?php echo 'Zip Code'; ?></label>
                                    <input type="text" class="form-control" name="cust_bil_zip" value="<?php echo $_SESSION['customer']['cust_bil_zip']; ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h3><?php echo 'Update Shipping Address'; ?></h3>
                                <div class="form-group">
                                    <label for=""><?php echo 'First Name'; ?></label>
                                    <input type="text" class="form-control" name="cust_shi_FirstName" value="<?php echo $_SESSION['customer']['cust_shi_FirstName']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for=""><?php echo 'Last Name'; ?></label>
                                    <input type="text" class="form-control" name="cust_shi_LastName" value="<?php echo $_SESSION['customer']['cust_shi_LastName']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for=""><?php echo 'Phone Number'; ?></label>
                                    <input type="text" class="form-control" name="cust_shi_phone" value="<?php echo $_SESSION['customer']['cust_shi_phone']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for=""><?php echo 'Country'; ?></label>
                                    <select name="cust_shi_country" class="form-control">
                                        <?php
                                        $statement = $pdo->prepare("SELECT * FROM tbl_country ORDER BY country_name ASC");
                                        $statement->execute();
                                        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($result as $row) {
                                            ?>
                                            <option value="<?php echo $row['country_id']; ?>" <?php if($row['country_id'] == $_SESSION['customer']['cust_shi_country']) {echo 'selected';} ?>><?php echo $row['country_name']; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for=""><?php echo 'Address'; ?></label>
                                    <textarea name="cust_shi_address" class="form-control" cols="30" rows="10" style="height:100px;"><?php echo $_SESSION['customer']['cust_shi_address']; ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for=""><?php echo 'City'; ?></label>
                                    <input type="text" class="form-control" name="cust_shi_city" value="<?php echo $_SESSION['customer']['cust_shi_city']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for=""><?php echo 'State'; ?></label>
                                    <input type="text" class="form-control" name="cust_shi_state" value="<?php echo $_SESSION['customer']['cust_shi_state']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for=""><?php echo 'Zip Code'; ?></label>
                                    <input type="text" class="form-control" name="cust_shi_zip" value="<?php echo $_SESSION['customer']['cust_shi_zip']; ?>">
                                </div>
                            </div>
                        </div>
                        <input type="submit" class="btn btn-primary" value="<?php echo 'Update'; ?>" name="form1">
                    </form>
                </div>                
            </div>
        </div>
    </div>
</div>


<?php require_once('footer.php'); ?>