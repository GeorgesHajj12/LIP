<?php require_once('header.php'); ?>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
foreach ($result as $row) {
    $banner_checkout = $row['banner_checkout'];
}
?>

<?php
if(!isset($_SESSION['cart_p_id'])) {
    header('location: cart.php');
    exit;
}


?>



<div class="page-banner" style="background-image: url(assets/uploads/<?php echo $banner_checkout; ?>)">
    <div class="overlay"></div>
    <div class="page-banner-inner">
        <h1><?php echo 'Checkout'; ?></h1>
    </div>
</div>

<div class="page">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                
                <?php if(!isset($_SESSION['customer'])): ?>
                    <p>
                        <a href="login.php" class="btn btn-md btn-danger"><?php echo 'Please login as customer to checkout'; ?></a>
                    </p>
                <?php else: ?>

                <h3 class="special"><?php echo 'Order Details'; ?></h3>
                <div class="cart">
                    <table class="table table-responsive table-hover table-bordered">
                        <tr>
                            <th><?php echo '#' ?></th>
                            <th><?php echo 'Photo'; ?></th>
                            <th><?php echo 'Product Name'; ?></th>
                            <th><?php echo 'Size'; ?></th>
                            <th><?php echo 'Color'; ?></th>
                            <th><?php echo 'Price'; ?></th>
                            <th><?php echo 'Quantity'; ?></th>
                            <th class="text-right"><?php echo 'Total'; ?></th>
                        </tr>
                         <?php
                         $payment_date = date('Y-m-d H:i:s');
                         $payment_id = time();
                        $table_total_price = 0;

                        $i=0;
                        foreach($_SESSION['cart_p_id'] as $key => $value) 
                        {
                            $i++;
                            $arr_cart_p_id[$i] = $value;
                            //$comp_id= $row["company_id"];
                        }

                        $i=0;
                        foreach($_SESSION['cart_size_id'] as $key => $value) 
                        {
                            $i++;
                            $arr_cart_size_id[$i] = $value;
                        }

                        $i=0;
                        foreach($_SESSION['cart_size_name'] as $key => $value) 
                        {
                            $i++;
                            $arr_cart_size_name[$i] = $value;
                        }

                        $i=0;
                        foreach($_SESSION['cart_color_id'] as $key => $value) 
                        {
                            $i++;
                            $arr_cart_color_id[$i] = $value;
                        }

                        $i=0;
                        foreach($_SESSION['cart_color_name'] as $key => $value) 
                        {
                            $i++;
                            $arr_cart_color_name[$i] = $value;
                        }

                        $i=0;
                        foreach($_SESSION['cart_p_qty'] as $key => $value) 
                        {
                            $i++;
                            $arr_cart_p_qty[$i] = $value;
                        }

                        $i=0;
                        foreach($_SESSION['cart_p_current_price'] as $key => $value) 
                        {
                            $i++;
                            $arr_cart_p_current_price[$i] = $value;
                        }

                        $i=0;
                        foreach($_SESSION['cart_p_name'] as $key => $value) 
                        {
                            $i++;
                            $arr_cart_p_name[$i] = $value;
                        }

                        $i=0;
                        foreach($_SESSION['cart_p_featured_photo'] as $key => $value) 
                        {
                            $i++;
                            $arr_cart_p_featured_photo[$i] = $value;
                        }

                        $i=0;
                        $statement = $pdo->prepare("SELECT * FROM products");
                        $statement->execute();
                        $result = $statement->fetchAll(PDO::FETCH_ASSOC);							
                        foreach ($result as $row) {
                            $i++;
                            $arr_p_id[$i] = $row['p_id'];
                            $arr_p_qty[$i] = $row['p_qty'];
                        
                        }

                        if (isset($_POST['form5'])) {

                            $statement = $pdo->prepare("INSERT INTO tbl_payment (   
	                            customer_id,
	                            customer_FirstName,
								customer_LastName,
	                            customer_email,
	                            payment_date,
	                            txnid, 
	                            paid_amount,
	                            card_number,
	                            card_cvv,
	                            card_month,
	                            card_year,
	                            bank_transaction_info,
	                            payment_method,
	                            payment_status,
	                            shipping_status,
	                            payment_id
	                        ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
	    $statement->execute(array(
	                            $_SESSION['customer']['cust_id'],
	                            $_SESSION['customer']['cust_FirstName'],
								$_SESSION['customer']['cust_LastName'],
	                            $_SESSION['customer']['cust_email'],
	                            ' ',
	                            ' ',
	                            ' ',
	                            ' ', 
	                            ' ',
	                            ' ', 
	                            ' ',
	                            " ",
	                            ' ',
	                            'Pending',
	                            'Pending',
	                            0
	                        ));

                            for($i=1;$i<=count($arr_cart_p_name);$i++) {
                                $statement = $pdo->prepare("INSERT INTO tbl_order (
                                                product_id,
                                                customer_id,
                                                product_name,
                                                size, 
                                                color,
                                                quantity, 
                                                unit_price, 
                                                payment_id,
                                                company_id,
                                                order_status_id
                                                ) 
                                                VALUES (?,?,?,?,?,?,?,?,?,?)");
                                $sql = $statement->execute(array(
                                                $arr_cart_p_id[$i],
                                                $_SESSION['customer']['cust_id'],
                                                $arr_cart_p_name[$i],
                                                $arr_cart_size_name[$i],
                                                $arr_cart_color_name[$i],
                                                $arr_cart_p_qty[$i],
                                                $arr_cart_p_current_price[$i],
                        
                                                0,
                                                $_SESSION['company']['company_id'],
                                                1
                                            ));
                        
                                // Update the stock
                                for($j=1;$j<=count($arr_p_id);$j++)
                                {
                                    if($arr_p_id[$j] == $arr_cart_p_id[$i]) 
                                    {
                                        $current_qty = $arr_p_qty[$j];
                                        break;
                                    }
                                }
                                $final_quantity = $current_qty - $arr_cart_p_qty[$i];
                                $statement = $pdo->prepare("UPDATE products SET p_qty=? WHERE p_id=?");
                                $statement->execute(array($final_quantity,$arr_cart_p_id[$i]));
                                
                            }
                            unset($_SESSION['cart_p_id']);
                            unset($_SESSION['cart_size_id']);
                            unset($_SESSION['cart_size_name']);
                            unset($_SESSION['cart_color_id']);
                            unset($_SESSION['cart_color_name']);
                            unset($_SESSION['cart_p_qty']);
                            unset($_SESSION['cart_p_current_price']);
                            unset($_SESSION['cart_p_name']);
                            unset($_SESSION['cart_p_featured_photo']);

                            header('location: index.php');
                        }
                        

                      
                        ?>
                        
                        <?php for($i=1;$i<=count($arr_cart_p_id);$i++): ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td>
                                <img src="assets/uploads/<?php echo $arr_cart_p_featured_photo[$i]; ?>" alt="">
                            </td>
                            <td><?php echo $arr_cart_p_name[$i]; ?></td>
                            <td><?php echo $arr_cart_size_name[$i]; ?></td>
                            <td><?php echo $arr_cart_color_name[$i]; ?></td>
                            <td><?php echo '$'; ?><?php echo $arr_cart_p_current_price[$i]; ?></td>
                            <td><?php echo $arr_cart_p_qty[$i]; ?></td>
                            <td class="text-right">
                                <?php
                                $row_total_price = $arr_cart_p_current_price[$i]*$arr_cart_p_qty[$i];
                                $table_total_price = $table_total_price + $row_total_price;
                                ?>
                                <?php echo '$'; ?><?php echo $row_total_price; ?>
                            </td>
                        </tr>
                        <?php endfor; ?>           
                        <tr>
                            <th colspan="7" class="total-text"><?php echo 'Sub Total'; ?></th>
                            <th class="total-amount"><?php echo '$'; ?><?php echo $table_total_price; ?></th>
                        </tr>
                        <?php
                        $statement = $pdo->prepare("SELECT * FROM tbl_shipping_cost WHERE country_id=?");
                        $statement->execute(array($_SESSION['customer']['cust_shi_country']));
                        $total = $statement->rowCount();
                        if($total) {
                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($result as $row) {
                                $shipping_cost = $row['amount'];
                            }
                        } else {
                            $statement = $pdo->prepare("SELECT * FROM tbl_shipping_cost_all WHERE sca_id=1");
                            $statement->execute();
                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($result as $row) {
                                $shipping_cost = $row['amount'];
                            }
                        }                        
                        ?>
                        <tr>
                            <td colspan="7" class="total-text"><?php echo 'Shipping Cost'; ?></td>
                            <td class="total-amount"><?php echo '$'; ?><?php echo $shipping_cost; ?></td>
                        </tr>
                        <tr>
                            <th colspan="7" class="total-text"><?php echo 'Total'; ?></th>
                            <th class="total-amount">
                                <?php
                                $final_total = $table_total_price+$shipping_cost;
                                ?>
                                <?php echo '$'; ?><?php echo $final_total; ?>
                            </th>
                        </tr>
                    </table> 
                </div>

                

                <div class="billing-address">
                    <div class="row">
                        <div class="col-md-6">
                            <h3 class="special"><?php echo 'Billing Address'; ?></h3>
                            <table class="table table-responsive table-bordered table-hover table-striped bill-address">
                                <tr>
                                    <td><?php echo 'First Name'; ?></td>
                                    <td><?php echo $_SESSION['customer']['cust_bil_FirstName']; ?></p></td>
                                </tr>
                                <tr>
                                    <td><?php echo 'Last Name'; ?></td>
                                    <td><?php echo $_SESSION['customer']['cust_bil_LastName']; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo 'Phone Number'; ?></td>
                                    <td><?php echo $_SESSION['customer']['cust_bil_phone']; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo 'Country'; ?></td>
                                    <td>
                                        <?php
                                        $statement = $pdo->prepare("SELECT * FROM tbl_country WHERE country_id=?");
                                        $statement->execute(array($_SESSION['customer']['cust_bil_country']));
                                        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($result as $row) {
                                            echo $row['country_name'];
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo 'Address'; ?></td>
                                    <td>
                                        <?php echo nl2br($_SESSION['customer']['cust_bil_address']); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo 'City'; ?></td>
                                    <td><?php echo $_SESSION['customer']['cust_bil_city']; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo 'State'; ?></td>
                                    <td><?php echo $_SESSION['customer']['cust_bil_state']; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo 'Zip Code'; ?></td>
                                    <td><?php echo $_SESSION['customer']['cust_bil_zip']; ?></td>
                                </tr>                                
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h3 class="special"><?php echo 'Shipping Address'; ?></h3>
                            <table class="table table-responsive table-bordered table-hover table-striped bill-address">
                                <tr>
                                    <td><?php echo 'First Name'; ?></td>
                                    <td><?php echo $_SESSION['customer']['cust_shi_FirstName']; ?></p></td>
                                </tr>
                                <tr>
                                    <td><?php echo 'Last Name'; ?></td>
                                    <td><?php echo $_SESSION['customer']['cust_shi_LastName']; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo 'Phone Number'; ?></td>
                                    <td><?php echo $_SESSION['customer']['cust_shi_phone']; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo 'Country'; ?></td>
                                    <td>
                                        <?php
                                        $statement = $pdo->prepare("SELECT * FROM tbl_country WHERE country_id=?");
                                        $statement->execute(array($_SESSION['customer']['cust_shi_country']));
                                        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($result as $row) {
                                            echo $row['country_name'];
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo 'Address'; ?></td>
                                    <td>
                                        <?php echo nl2br($_SESSION['customer']['cust_shi_address']); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo 'City'; ?></td>
                                    <td><?php echo $_SESSION['customer']['cust_shi_city']; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo 'State'; ?></td>
                                    <td><?php echo $_SESSION['customer']['cust_shi_state']; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo 'Zip Code'; ?></td>
                                    <td><?php echo $_SESSION['customer']['cust_shi_zip']; ?></td>
                                </tr> 
                            </table>
                        </div>
                    </div>                    
                </div>

                

                <div class="cart-buttons">
                    <ul>
                        <li><a href="cart.php" class="btn btn-primary"><?php echo 'Back to Cart'; ?></a></li>
                    </ul>
                </div>

               
                 </form>
				<div class="clear"></div>
                <h3 class="special"><?php echo 'Payment Section'; ?></h3>
                <div class="row">
                    
                    	<?php
		                $checkout_access = 1;
		                if(
		                    ($_SESSION['customer']['cust_bil_FirstName']=='') ||
		                    ($_SESSION['customer']['cust_bil_LastName']=='') ||
		                    ($_SESSION['customer']['cust_bil_phone']=='') ||
		                    ($_SESSION['customer']['cust_bil_country']=='') ||
		                    ($_SESSION['customer']['cust_bil_address']=='') ||
		                    ($_SESSION['customer']['cust_bil_city']=='') ||
		                    ($_SESSION['customer']['cust_bil_state']=='') ||
		                    ($_SESSION['customer']['cust_bil_zip']=='') ||
		                    ($_SESSION['customer']['cust_shi_FirstName']=='') ||
		                    ($_SESSION['customer']['cust_shi_LastName']=='') ||
		                    ($_SESSION['customer']['cust_shi_phone']=='') ||
		                    ($_SESSION['customer']['cust_shi_country']=='') ||
		                    ($_SESSION['customer']['cust_shi_address']=='') ||
		                    ($_SESSION['customer']['cust_shi_city']=='') ||
		                    ($_SESSION['customer']['cust_shi_state']=='') ||
		                    ($_SESSION['customer']['cust_shi_zip']=='')
		                ) {
		                    $checkout_access = 0;
		                }
		                ?>
		                <?php if($checkout_access == 0): ?>
		                	<div class="col-md-12">
				                <div style="color:red;font-size:22px;margin-bottom:50px;">
			                        You must have to fill up all the billing and shipping information from your dashboard panel in order to checkout the order. Please fill up the information going to <a href="customer-billing-shipping-update.php" style="color:red;text-decoration:underline;">this link</a>.
			                    </div>
	                    	</div>
	                	<?php else: ?>
		                	<div class="col-md-4">
		                		
	                            <div class="row">

	                                <div class="col-md-12 form-group">
	                                    <label for=""><?php echo 'Select Payment Method'; ?> *</label>
	                                    <select name="payment_method" class="form-control select2" id="advFieldsStatus">
	                                        <option value=""><?php echo 'Select a Method'; ?></option>
	                                        <option value="PayPal"><?php echo 'PayPal'; ?></option>
	                                        <option value="Bank Deposit"><?php echo 'Bank Deposit'; ?></option>
	                                    </select>
	                                </div>

                                    <form class="paypal" action="<?php echo BASE_URL; ?>payment/paypal/payment_process.php" method="post" id="paypal_form" target="_blank">
                                        <input type="hidden" name="cmd" value="_xclick" />
                                        <input type="hidden" name="no_note" value="1" />
                                        <input type="hidden" name="lc" value="UK" />
                                        <input type="hidden" name="currency_code" value="USD" />
                                        <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest" />

                                        <input type="hidden" name="final_total" value="<?php echo $final_total; ?>">
                                        <div class="col-md-12 form-group">
                                            <input type="submit" class="btn btn-primary" value="<?php echo 'Pay Now'; ?>" name="form1">
                                        </div>
                                    </form>



                                    <form action="payment/bank/init.php" method="post" id="bank_form">
                                        <input type="hidden" name="amount" value="<?php echo $final_total; ?>">
                                        <div class="col-md-12 form-group">
                                            <label for=""><?php echo 'Send to this Details'; ?></span></label><br>
                                            <?php
                                            $statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
                                            $statement->execute();
                                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                            foreach ($result as $row) {
                                                echo nl2br($row['bank_detail']);
                                            }
                                            ?>
                                        </div>
                                        <div class="col-md-12 form-group">
                                            <label for=""><?php echo 'Transaction Information'; ?> <br><span style="font-size:12px;font-weight:normal;">(<?php echo 'Include transaction id and other information correctly'; ?>)</span></label>
                                            <textarea name="transaction_info" class="form-control" cols="30" rows="10"></textarea>
                                        </div>
                                        <div class="col-md-12 form-group">
                                            <input type="submit" class="btn btn-primary" value="<?php echo 'Pay Now'; ?>" name="form3">
                                        </div>
                                    </form>
	                                
	                            </div>
		                            
		                        
		                    </div>
		                <?php endif; ?>
                        
                </div>
                

                <?php endif; ?>

            </div>
        </div>
    </div>
</div>


<?php require_once('footer.php'); ?>