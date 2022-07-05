<?php require_once('reg_header.php'); ?>
<?php
include("../New folder (5)/sendEmail.php");
if (isset($_POST['form1'])) {

    $valid = 1;

    if(empty($_POST['company_name'])) {
        $valid = 0;
        $error_message = 'Company Name can not be empty.'."<br>";
    }

    if(empty($_POST['company_email'])) {
        $valid = 0;
        $error_message .= 'Email Address can not be empty'."<br>";
    } else {
        if (filter_var($_POST['company_email'], FILTER_VALIDATE_EMAIL) === false) {
            $valid = 0;
            $error_message = 'Email address must be valid.'."<br>";
        } else {
            $statement = $pdo->prepare("SELECT * FROM company WHERE company_email=?");
            $statement->execute(array($_POST['company_email']));
            $total = $statement->rowCount();                            
            if($total) {
                $valid = 0;
                $error_message = 'Email Address Already Exists'."<br>";
            }
        }
    }

    if(empty($_POST['company_phone'])) {
        $valid = 0;
        $error_message = 'Phone Number can not be empty.'."<br>";
    }

    if(empty($_POST['company_address'])) {
        $valid = 0;
        $error_message = 'Address can not be empty.'."<br>";
    }

    if(empty($_POST['company_type'])) {
        $valid = 0;
        $error_message = 'You must select a type.'."<br>";
    }

    if(empty($_POST['company_city'])) {
        $valid = 0;
        $error_message = 'City can not be empty.'."<br>";
    }

    if(empty($_POST['industrial_certification'])) {
        $valid = 0;
        $error_message = 'industrial_certification can not be empty.'."<br>";
    }
    /*
    if(empty($_POST['cust_zip'])) {
        $valid = 0;
        $error_message = 'Zip Code can not be empty.'."<br>";
    }*/

    if( empty($_POST['company_password']) || empty($_POST['company_re_password']) ) {
        $valid = 0;
        $error_message = 'Password can not be empty.'."<br>";
    }

    if( !empty($_POST['company_password']) && !empty($_POST['company_re_password']) ) {
        if($_POST['company_password'] != $_POST['company_re_password']) {
            $valid = 0;
            $error_message ='Passwords do not match.'."<br>";
        }
    }

    if($valid == 1) {

        $token = md5(time());
        $company_datetime = date('Y-m-d h:i:s');
        $company_timestamp = time();

        // saving into the database
        $statement = $pdo->prepare("INSERT INTO `company`(
                                            `company_name`,
                                            `company_phone`,
                                            `company_address`,
                                            `company_city`,
                                            `company_type`,
                                            `company_email`,
                                            `company_password`,
                                            `industrial certification`,
                                            `company_status_id`)
                  VALUES (?,?,?,?,?,?,?,?,?)");
        $statement->execute(array(
                                        strip_tags($_POST['company_name']),                                       
                                        strip_tags($_POST['company_phone']),                                       
                                        strip_tags($_POST['company_address']),
                                        strip_tags($_POST['company_city']),
                                        strip_tags($_POST['company_type']),
                                        strip_tags($_POST['company_email']),
                                        md5($_POST['company_password']),  
                                        strip_tags($_POST['industrial_certification']),                       
                                        0
                                    ));
                                    header('location: login.php');
        // Send email for confirmation of the account
        $to = $_POST['company_email'];
        
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
        $mybody='Thank u for your registration';
        sendEmailKK($_POST['company_name'],$_POST['company_email'],$mybody);
        unset($_POST['company_name']);
        unset($_POST['company_email']);
        unset($_POST['company_phone']);
        unset($_POST['company_address']);
        unset($_POST['company_city']);
        unset($_POST['industrial_certification']);
  
        $success_message = 'Your registration is completed.';
    }
}
?>



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
                                    <label for=""><?php echo 'Company Name'; ?> *</label>
                                    <input type="text" class="form-control" name="company_name" value="<?php if(isset($_POST['company_name'])){echo $_POST['company_name'];} ?>">
                                </div>
                                
                                <div class="col-md-6 form-group">
                                    <label for=""><?php echo 'Email Address'; ?> *</label>
                                    <input type="email" class="form-control" name="company_email" value="<?php if(isset($_POST['company_email'])){echo $_POST['company_email'];} ?>">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for=""><?php echo 'Phone Number'; ?> *</label>
                                    <input type="text" class="form-control" name="company_phone" value="<?php if(isset($_POST['company_phone'])){echo $_POST['company_phone'];} ?>">
                                </div>
                                <div class="col-md-12 form-group">
                                    <label for=""><?php echo 'Address'; ?> *</label>
                                    <textarea name="company_address" class="form-control" cols="30" rows="10" style="height:70px;"><?php if(isset($_POST['company_address'])){echo $_POST['company_address'];} ?></textarea>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for=""><?php echo 'Company type'; ?> *</label>
                                    <select name="company_type" class="form-control select2">
                                        <option value="">Select Company type</option>
                                    <?php
                                    $statement = $pdo->prepare("SELECT * FROM company_type ORDER BY company_type_name ASC");
                                    $statement->execute();
                                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
                                    foreach ($result as $row) {
                                        ?>
                                        <option value="<?php echo $row['company_type_id']; ?>"><?php echo $row['company_type_name']; ?></option>
                                        <?php
                                    }
                                    ?>    
                                    </select>                                    
                                </div>
                                
                                <div class="col-md-6 form-group">
                                    <label for=""><?php echo 'City'; ?> *</label>
                                    <input type="text" class="form-control" name="company_city" value="<?php if(isset($_POST['company_city'])){echo $_POST['company_city'];} ?>">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for=""><?php echo 'industrial certification'; ?> *</label>
                                    <input type="file" class="form-control" name="industrial_certification" accept="image/jpg, image/jpeg, image/png" value="<?php if(isset($_POST['industrial_certification'])){echo $_POST['industrial_certification'];} ?>">
                                </div>
                                
                                <div class="col-md-6 form-group">
                                    <label for=""><?php echo 'Password'; ?> *</label>
                                    <input type="password" class="form-control" name="company_password">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for=""><?php echo 'Retype Password'; ?> *</label>
                                    <input type="password" class="form-control" name="company_re_password">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for=""></label>
                                    <input type="submit" class="btn btn-danger" value="<?php echo 'Register'; ?>" name="form1">
                                    <a href="login.php">go back to login </a>
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