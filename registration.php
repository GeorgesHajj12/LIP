<?php require_once('header.php'); ?>

<?php
include("New folder (5)/CustRegisEmail.php");
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
foreach ($result as $row) {
    $banner_registration = $row['banner_registration'];
}
?>

<?php
if (isset($_POST['form1'])) {

    $valid = 1;

    if(empty($_POST['cust_FirstName'])) {
        $valid = 0;
        $error_message = 'Customer First Name can not be empty.'."<br>";
    }

    if(empty($_POST['cust_email'])) {
        $valid = 0;
        $error_message .= 'Email Address can not be empty'."<br>";
    } else {
        if (filter_var($_POST['cust_email'], FILTER_VALIDATE_EMAIL) === false) {
            $valid = 0;
            $error_message = 'Email address must be valid.'."<br>";
        } else {
            $statement = $pdo->prepare("SELECT * FROM customer WHERE cust_email=?");
            $statement->execute(array($_POST['cust_email']));
            $total = $statement->rowCount();                            
            if($total) {
                $valid = 0;
                $error_message = 'Email Address Already Exists'."<br>";
            }
        }
    }

    if(empty($_POST['cust_phone'])) {
        $valid = 0;
        $error_message = 'Phone Number can not be empty.'."<br>";
    }

    if(empty($_POST['cust_address'])) {
        $valid = 0;
        $error_message = 'Address can not be empty.'."<br>";
    }

    if(empty($_POST['cust_country'])) {
        $valid = 0;
        $error_message = 'You must select a country.'."<br>";
    }

    if(empty($_POST['cust_city'])) {
        $valid = 0;
        $error_message = 'City can not be empty.'."<br>";
    }

    if(empty($_POST['cust_state'])) {
        $valid = 0;
        $error_message = 'State can not be empty.'."<br>";
    }

    if(empty($_POST['cust_zip'])) {
        $valid = 0;
        $error_message = 'Zip Code can not be empty.'."<br>";
    }

    if( empty($_POST['cust_password']) || empty($_POST['cust_re_password']) ) {
        $valid = 0;
        $error_message = 'Password can not be empty.'."<br>";
    }

    if( !empty($_POST['cust_password']) && !empty($_POST['cust_re_password']) ) {
        if($_POST['cust_password'] != $_POST['cust_re_password']) {
            $valid = 0;
            $error_message ='Passwords do not match.'."<br>";
        }
    }

    if($valid == 1) {

        $token = md5(time());
        $cust_datetime = date('Y-m-d h:i:s');
        $cust_timestamp = time();

        // saving into the database
        $statement = $pdo->prepare("INSERT INTO customer (
                                        cust_FirstName,
                                        cust_LastName,
                                        cust_email,
                                        cust_phone,
                                        cust_country,
                                        cust_address,
                                        cust_city,
                                        cust_state,
                                        cust_zip,
                                        cust_bil_FirstName,
                                        cust_bil_LastName,
                                        cust_bil_phone,
                                        cust_bil_country,
                                        cust_bil_address,
                                        cust_bil_city,
                                        cust_bil_state,
                                        cust_bil_zip,
                                        cust_shi_FirstName,
                                        cust_shi_LastName,
                                        cust_shi_phone,
                                        cust_shi_country,
                                        cust_shi_address,
                                        cust_shi_city,
                                        cust_shi_state,
                                        cust_shi_zip,
                                        cust_password,
                                        cust_token,
                                        cust_datetime,
                                        cust_timestamp,
                                        cust_status
                                    ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $statement->execute(array(
                                        strip_tags($_POST['cust_FirstName']),
                                        strip_tags($_POST['cust_LastName']),
                                        strip_tags($_POST['cust_email']),
                                        strip_tags($_POST['cust_phone']),
                                        strip_tags($_POST['cust_country']),
                                        strip_tags($_POST['cust_address']),
                                        strip_tags($_POST['cust_city']),
                                        strip_tags($_POST['cust_state']),
                                        strip_tags($_POST['cust_zip']),
                                        '',
                                        '',
                                        '',
                                        '',
                                        '',
                                        '',
                                        '',
                                        '',
                                        '',
                                        '',
                                        '',
                                        '',
                                        '',
                                        '',
                                        '',
                                        '',
                                        md5($_POST['cust_password']),
                                        $token,
                                        $cust_datetime,
                                        $cust_timestamp,
                                        0
                                    ));
                                    header('location: login.php');
        // Send email for confirmation of the account
        $to = $_POST['cust_email'];
        
        $subject = 'Registration Email Confirmation for YOUR WEBSITE';
        $verify_link ='verify.php?email='.$to.'&token='.$token;
        $message = 'Thank you for your registration! Your account has been created. To active your account click on the link below:'.'<br><br>

<a href="'.$verify_link.'">'.$verify_link.'</a>';

        $headers = "From: noreply@" . BASE_URL . "\r\n" .
                   "Reply-To: noreply@" . BASE_URL . "\r\n" .
                   "X-Mailer: PHP/" . phpversion() . "\r\n" . 
                   "MIME-Version: 1.0\r\n" . 
                   "Content-Type: text/html; charset=ISO-8859-1\r\n";
        
        // Sending Email
        
        mail($to, $subject, $message, $headers);
        sendEmailRegis($_POST['cust_email']);
        unset($_POST['cust_FirstName']);
        unset($_POST['cust_LastName']);
        unset($_POST['cust_email']);
        unset($_POST['cust_phone']);
        unset($_POST['cust_address']);
        unset($_POST['cust_city']);
        unset($_POST['cust_state']);
        unset($_POST['cust_zip']);

        $success_message = 'Your registration is completed.';
    }
}
?>

<div class="page-banner" style="background-color:#444;background-image: url(assets/uploads/<?php echo $banner_registration; ?>);">
    <div class="inner">
        <h1><?php echo 'Customer Registration'; ?></h1>
    </div>
</div>

<div class="page">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="user-content">

                    

                    <form action="" method="post">
                        <?php $csrf->echoInputField(); ?>
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-8">
                                
                                <?php
                                if($error_message != '') {
                                    echo "<div class='error' style='padding: 10px;background:#f1f1f1;margin-bottom:20px;'>".$error_message."</div>";
                                }
                                if($success_message != '') {
                                    echo "<div class='success' style='padding: 10px;background:#f1f1f1;margin-bottom:20px;'>".$success_message."</div>";
                                }
                                ?>

                                <div class="col-md-6 form-group">
                                    <label for=""><?php echo 'First Name'; ?> *</label>
                                    <input type="text" class="form-control" name="cust_FirstName" value="<?php if(isset($_POST['cust_FirstName'])){echo $_POST['cust_FirstName'];} ?>">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for=""><?php echo 'Last Name'; ?></label>
                                    <input type="text" class="form-control" name="cust_LastName" value="<?php if(isset($_POST['cust_LastName'])){echo $_POST['cust_LastName'];} ?>">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for=""><?php echo 'Email Address'; ?> *</label>
                                    <input type="email" class="form-control" name="cust_email" value="<?php if(isset($_POST['cust_email'])){echo $_POST['cust_email'];} ?>">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for=""><?php echo 'Phone Number'; ?> *</label>
                                    <input type="text" class="form-control" name="cust_phone" value="<?php if(isset($_POST['cust_phone'])){echo $_POST['cust_phone'];} ?>">
                                </div>
                                <div class="col-md-12 form-group">
                                    <label for=""><?php echo 'Address'; ?> *</label>
                                    <textarea name="cust_address" class="form-control" cols="30" rows="10" style="height:70px;"><?php if(isset($_POST['cust_address'])){echo $_POST['cust_address'];} ?></textarea>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for=""><?php echo 'Country'; ?> *</label>
                                    <select name="cust_country" class="form-control select2">
                                        <option value="">Select country</option>
                                    <?php
                                    $statement = $pdo->prepare("SELECT * FROM tbl_country ORDER BY country_name ASC");
                                    $statement->execute();
                                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
                                    foreach ($result as $row) {
                                        ?>
                                        <option value="<?php echo $row['country_id']; ?>"><?php echo $row['country_name']; ?></option>
                                        <?php
                                    }
                                    ?>    
                                    </select>                                    
                                </div>
                                
                                <div class="col-md-6 form-group">
                                    <label for=""><?php echo 'City'; ?> *</label>
                                    <input type="text" class="form-control" name="cust_city" value="<?php if(isset($_POST['cust_city'])){echo $_POST['cust_city'];} ?>">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for=""><?php echo 'State'; ?> *</label>
                                    <input type="text" class="form-control" name="cust_state" value="<?php if(isset($_POST['cust_state'])){echo $_POST['cust_state'];} ?>">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for=""><?php echo 'Zip Code'; ?> *</label>
                                    <input type="text" class="form-control" name="cust_zip" value="<?php if(isset($_POST['cust_zip'])){echo $_POST['cust_zip'];} ?>">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for=""><?php echo 'Password'; ?> *</label>
                                    <input type="password" class="form-control" name="cust_password">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for=""><?php echo 'Retype Password'; ?> *</label>
                                    <input type="password" class="form-control" name="cust_re_password">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for=""></label>
                                    <input type="submit" class="btn btn-danger" value="<?php echo 'Register'; ?>" name="form1">
                                </div>
                            </div>
                        </div>                        
                    </form>
                </div>                
            </div>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>