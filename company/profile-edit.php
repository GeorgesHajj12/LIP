<?php require_once('header.php'); ?>



<?php
if (isset($_POST['form1'])) {

    $valid = 1;

    if(empty($_POST['company_name'])) {
        $valid = 0;
        $error_message = 'Company Name can not be empty.'."<br>";
    }

    /*if(empty($_POST['company_email'])) {
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
    }*/

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

    /*if(empty($_POST['industrial_certification'])) {
        $valid = 0;
        $error_message = 'industrial_certification can not be empty.'."<br>";
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
    // update data into the database
    $statement = $pdo->prepare("UPDATE `company` SET 
                            `company_name`=?,                             
                            `company_phone`=?, 
                            `company_address`=?, 
                            `company_city`=?, 
                            `company_type`=?, 
                            
                            
                            `company_password`=?

                            WHERE `company_id`=?");
    $statement->execute(array(
                            strip_tags($_POST['company_name']),
                            strip_tags($_POST['company_phone']),
                            strip_tags($_POST['company_address']),
                            strip_tags($_POST['company_city']),
                            strip_tags($_POST['company_type']),
                            
                            
                            md5($_POST['company_password']),
                            
                            $_SESSION['company']['company_id']
                        ));  
   
    $success_message = 'Company profile Information is updated successfully.';

    $_SESSION['company']['company_name'] = strip_tags($_POST['company_name']);
    $_SESSION['company']['company_phone'] = strip_tags($_POST['company_phone']);
    $_SESSION['company']['company_address'] = strip_tags($_POST['company_address']);
    $_SESSION['company']['company_city'] = strip_tags($_POST['company_city']);
    $_SESSION['company']['company_type'] = strip_tags($_POST['company_type']);
 
    $_SESSION['company']['company_password'] = strip_tags($_POST['company_password']);
    
                    }
}
?>
<section class="content">
        <div class="row">            
            <div class="col-md-12"> 
            <div class="nav-tabs-custom">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab_1" data-toggle="tab">Update Information</a></li>
						</ul> 
            
            
                    <?php
                    if($error_message != '') {
                        echo "<div class='error' style='padding: 10px;background:#f1f1f1;margin-bottom:20px;'>".$error_message."</div>";
                    }
                    if($success_message != '') {
                        echo "<div class='success' style='padding: 10px;background:#f1f1f1;margin-bottom:20px;'>".$success_message."</div>";
                    }
                    ?>
                    <div class="tab-content">
          				<div class="tab-pane active" id="tab_1">
                    <form action="" method="post">
                        <?php $csrf->echoInputField(); ?>
                        <div class="row">
                            <div class="col-md-6">
                                <h3><?php echo 'Update Info'; ?></h3>
                                <div class="form-group">
                                    <label for=""><?php echo 'Company Name'; ?></label>
                                    <input type="text" class="form-control" name="company_name" value="<?php echo $_SESSION['company']['company_name']; ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for=""><?php echo 'Phone Number'; ?></label>
                                    <input type="text" class="form-control" name="company_phone" value="<?php echo $_SESSION['company']['company_phone']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for=""><?php echo 'company type'; ?></label>
                                    <select name="company_type" class="form-control">
                                        <?php
                                        $statement = $pdo->prepare("SELECT * FROM company_type ORDER BY company_type_name ASC");
                                        $statement->execute();
                                        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($result as $row) {
                                            ?>
                                            <option value="<?php echo $row['company_type_id']; ?>" <?php if($row['company_type_id'] == $_SESSION['company']['company_type']) {echo 'selected';} ?>><?php echo $row['company_type_name']; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for=""><?php echo 'Address'; ?></label>
                                    <textarea name="company_address" class="form-control" cols="30" rows="10" style="height:100px;"><?php echo $_SESSION['company']['company_address']; ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for=""><?php echo 'City'; ?></label>
                                    <input type="text" class="form-control" name="company_city" value="<?php echo $_SESSION['company']['company_city']; ?>">
                                </div>

                               

                                
                                
                                <form class="form-horizontal" action="" method="post">
							<div class="box box-info">
								<div class="box-body">
									<div class="form-group">
										<label for="" class="col-sm-2 control-label">Password </label>
										<div class="col-sm-4">
											<input type="password" class="form-control" name="company_password">
										</div>
									</div>
									<div class="form-group">
										<label for="" class="col-sm-2 control-label">Retype Password </label>
										<div class="col-sm-4">
											<input type="password" class="form-control" name="company_re_password">
										</div>
									</div>
							        <div class="form-group">
										<label for="" class="col-sm-2 control-label"></label>
										<div class="col-sm-6">
                                
                        <input type="submit" class="btn btn-primary" value="<?php echo 'Update'; ?>" name="form1">
                    </form>
                </div>                
            </div>
        </div>
    </div>
</div>


<?php require_once('footer.php'); ?>