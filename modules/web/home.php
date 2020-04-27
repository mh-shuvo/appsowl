<?php defined('_AZ') or die('Restricted access'); 
	include dirname(__FILE__) .'/includes/header.php';
	if (app('login')->isLoggedIn()) {
		redirect("/verify");
	}
	
?>

<style>
	.thumbnail{height:200px;}
	.benifit ul li{font-size:20px;font-family:sans serif; line-height:51px;font-weight:bold;}
	.testimonials input{color:black;}
</style>

    <!--LOGIN-SIGNUP START -->
    <section class="gray-section" id="page-top">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-lg-6">
                    <br>
                    <p style="font-size:18px; color:#1ab394; font-family:arial black;">
                        <b>Software helps are in digitalization and development of our life.</b>
                    </p>
                    <br>
                    <br>
                    <img src="assets/system/img/landing/Software-PNG.png" class="img-responsive img-circle" alt="Responsive image">
                </div>
                <div class="col-md-6 col-lg-6">
                    <!--h2 style="color:#1ab394; font-family:arial black;"><strong>Sign In</strong></h2-->
                    <br>
                    <div id="login">
                        <form accept-charset="utf-8">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <input type="text" name="username" class="form-control" placeholder="Mobile/Email" required="" autofocus="">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <input type="password" name="password" class="form-control" placeholder="Password" required="">
                                        <a href="/forget-password" class="font-italic"><?php echo trans('forgot_password'); ?></a>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <button type="submit" name="login" class="btn btn-default">Log In</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <h2 style="color:#1ab394; font-family:arial black;"><strong>Create an account</strong></h2>
                    <div id="create">
                        <form accept-charset="utf-8">
                            <div class="row">
                                <div class="col-md-5">
                                    <input type="text" name="reg_first_name" class="form-control" placeholder="First Name" required="">
                                </div>
                                <div class="col-md-5">
                                    <input type="text" name="reg_last_name" class="form-control" placeholder="Last Name" required="">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row-fluid">
                                                <select class="form-control" name="country_code" id="country_code" onchange="getcountrycode();" data-show-subtext="true" data-live-search="true">
                                                    <option value="" selected>Select Country</option>
                                                    <?php $getcountry = app('admin')->getall('as_country'); 
													foreach($getcountry as $getcountry){ ?>
                                                        <option value="+<?php echo $getcountry['phonecode']; ?>">
                                                            <?php echo $getcountry['name']; ?>
                                                        </option>
                                                        <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="text" name="reg_phone" id="reg_phone" class="form-control" placeholder="Mobile Number" required="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <input type="email" name="reg_email" class="form-control" placeholder="Email Address" required="">
                                    </div>
                                </div>
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <input type="password" name="password" class="form-control" placeholder="New password" required="">
                                    </div>
                                </div>
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password" required="">
                                    </div>
                                </div>
                                <div class="col-md-10">
									<div class="form-group">
										<div class="col-sm-1">
											<input type="checkbox" id="reg_terms">
										</div>
										<div class="col-sm-11">
											<label>I agree to Appsowl <a href="/terms/">terms of use</a> and have read and acknowledged Appsowl <a href="/privacy/">privacy policy.</a></label>
										</div>
									</div>
								</div>
                                <div class="col-md-5">
                                    <button type="submit" disabled name="submit" class="btn btn-primary btn-lg btn-block" id="reg_submit"><b>Submit</b></button>
                                </div>

                            </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <br>
    </section>

    <section class="features">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="navy-line"></div>
                    <h1><strong>Software Features</strong></h1>
                    <p>Software developed for retail business. </p>
                </div>
            </div>
            <div class='row'>
                <div class='col-sm-12'>
                    <div class="row">
                        <div class="col-xs-12 col-md-3">
                            <a href="#" class="thumbnail">
                                <img src="assets/system/img/icon/user.png" alt="...">
                                <br>
                                <center>
                                    <p><b>User Management system</b></p>
                                </center>
                            </a>
                        </div>

                        <div class="col-xs-12 col-md-3">
                            <a href="#" class="thumbnail">
                                <img src="assets/system/img/icon/product.png" alt="...">
                                <br>
                                <center>
                                    <p><b>Product Management</b></p>
                                </center>
                            </a>
                        </div>

                        <div class="col-xs-12 col-md-3">
                            <a href="#" class="thumbnail">
                                <img src="assets/system/img/icon/supplierr.png" alt="...">
                                <br>
                                <center>
                                    <p><b>Supplier Management</b></p>
                                </center>
                            </a>
                        </div>
						<div class="col-xs-12 col-md-3">
                            <a href="#" class="thumbnail">
                                <img src="assets/system/img/icon/supplier.png" alt="...">
                                <br>
                                <center>
                                    <p><b>Customer Management</b></p>
                                </center>
                            </a>
                        </div>

                        <div class="col-xs-12 col-md-3">
                            <a href="#" class="thumbnail">
                                <img src="assets/system/img/icon/sale.png" alt="...">
                                <br>
                                <center>
                                    <p><b>Sales Tracking</b></p>
                                </center>
                            </a>
                        </div>
						
						<div class="col-xs-12 col-md-3">
                            <a href="#" class="thumbnail">
                                <img src="assets/system/img/icon/billing.png" alt="...">
                                <br>
                                <center>
                                    <p><b>Invoice & Billing</b></p>
                                </center>
                            </a>
                        </div>

                        <div class="col-xs-12 col-md-3">
                            <a href="#" class="thumbnail">
                                <img src="assets/system/img/icon/basket.png" alt="...">
								<br>
                                <center>
                                    <p><b>Purchas Management</b></p>
                                </center>
                            </a>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <a href="#" class="thumbnail">
                                <img src="assets/system/img/icon/calculator.png" alt="...">
                                <br>
                                <center>
                                    <p><b>Accounts Management</b></p>
                                </center>
                            </a>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <a href="#" class="thumbnail">
                                <img src="assets/system/img/icon/inventory2.png" alt="...">
								<br>
                                <center>
                                    <p><b>Inventory Management</b></p>
                                </center>
                            </a>
                        </div>
						 <div class="col-xs-12 col-md-3">
                            <a href="#" class="thumbnail">
                                <img src="assets/system/img/icon/crm.png" alt="...">
								<br>
                                <center>
                                    <p><b>CRM Management</b></p>
                                </center>
                            </a>
                        </div>
						<div class="col-xs-12 col-md-3">
                            <a href="#" class="thumbnail">
                                <img src="assets/system/img/icon/category.png" alt="...">
								<br>
                                <center>
                                    <p><b>Category Management</b></p>
                                </center>
                            </a>
                        </div>
						<div class="col-xs-12 col-md-3">
                            <a href="#" class="thumbnail">
                                <img src="assets/system/img/icon/stock.png" alt="...">
                                <br>
                                <center>
                                    <p><b>Stock Management</b></p>
                                </center>
                            </a>
                        </div>
						<div class="col-xs-12 col-md-3">
                            <a href="#" class="thumbnail">
                                <img src="assets/system/img/icon/report.png" alt="...">
                                <br>
                                <center>
                                    <p><b>Reporting System</b></p>
                                </center>
                            </a>
                        </div>

						<div class="col-xs-12 col-md-3">
                            <a href="#" class="thumbnail">
                                <img src="assets/system/img/icon/multistore.png" alt="...">
                                <br>
                                <center>
                                    <p><b>MultiStore Management</b></p>
                                </center>
                            </a>
                        </div>
						<div class="col-xs-12 col-md-3">
                            <a href="#" class="thumbnail">
                                <img src="assets/system/img/icon/warehouse.png" alt="...">
                                <br>
                                <center>
                                    <p><b>Multiple Warehouse</b></p>
                                </center>
                            </a>
                        </div>
						<div class="col-xs-12 col-md-3">
                            <a href="#" class="thumbnail">
                                <img src="assets/system/img/icon/payment.jpg" alt="...">
                                <br>
                                <center>
                                    <p><b>Multiple Payment Method</b></p>
                                </center>
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class='gray-section retails'>
        <div class='row'>
            <div class="col-md-12 col-lg-12 container">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12 text-center">
                            <div class="navy-line"></div>
                            <h1><strong>THE ONE POS SYSTEM FOR RETAIL SUCCESS, FROM 1 STORE TO 100+</strong></h1>
                            <p>Check out these examples of clever stores around the world.</p>
                        </div>
                    </div>
					<div class="row">
						<?php
							$getCategory=app('admin')->getwhereand('as_software_variation','software_id','1','software_variation_status','active');
							foreach($getCategory as $category){
							?>
							<div class="col-xs-12 col-md-3">
								<a href="javascript:void()" class="thumbnail">
									<img src="assets/system/img/icon/category_icon/<?php echo $category['software_variation_icon']; ?>" class="rounded">
									<br>
									<center>
										<p><b><?php echo $category['software_variation_name'];?></b></p>
									</center>
								</a>
							</div>
						<?php } ?>
							<div class="col-xs-12 col-md-3">
								<a href="javascript:void()" class="thumbnail">
									<img src="assets/system/img/icon/category_icon/custom.png" class="rounded">
									<br>
									<center>
										<p><b>Custom</b></p>
									</center>
								</a>
							</div>
					</div>
                    <!--<div class="row">
                        <div class="col-xs-12 col-md-3">
                            <a href="#" class="thumbnail">
                                <img src="assets/system/img/landing/industry_fashion.png" alt="...">
                                <center>
                                    <p><b>FASHION & APPAREL</b></p>
                                </center>
                            </a>
                        </div>

                        <div class="col-xs-12 col-md-3">
                            <a href="#" class="thumbnail">
                                <img src="assets/system/img/landing/industry_shoes.png" alt="...">
                                <center>
                                    <p><b>Shoe Stores</b></p>
                                </center>
                            </a>
                        </div>

                        <div class="col-xs-12 col-md-3">
                            <a href="#" class="thumbnail">
                                <img src="assets/system/img/landing/industry_sports.png" alt="...">
                                <center>
                                    <p><b>Sports & Outdoors</b></p>
                                </center>
                            </a>
                        </div>

                        <div class="col-xs-12 col-md-3">
                            <a href="#" class="thumbnail">
                                <img src="assets/system/img/landing/industry_health_and_beauty.png" alt="...">
                                <center>
                                    <p><b>Health & Beauty</b></p>
                                </center>
                            </a>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <a href="#" class="thumbnail">
                                <img src="assets/system/img/landing/electronics_grid.png" alt="...">
                                <center>
                                    <p><b>Electronics</b></p>
                                </center>
                            </a>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <a href="#" class="thumbnail">
                                <img src="assets/system/img/landing/industry_jewelry.png" alt="...">
                                <center>
                                    <p><b>jewelry</b></p>
                                </center>
                            </a>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <a href="#" class="thumbnail">
                                <img src="assets/system/img/landing/industry_homeware.png" alt="...">
                                <center>
                                    <p><b>Homeware Stores</b></p>
                                </center>
                            </a>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <a href="#" class="thumbnail">
                                <img src="assets/system/img/landing/food_grid.png" alt="...">
                                <center>
                                    <p><b>Food and Drink Retail</b></p>
                                </center>
                            </a>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <a href="#" class="thumbnail">
                                <img src="assets/system/img/landing/bikes_grid.png" alt="...">
                                <center>
                                    <p><b>Bike Shops</b></p>
                                </center>
                            </a>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <a href="#" class="thumbnail">
                                <img src="assets/system/img/landing/fitness_grid.png" alt="...">
                                <center>
                                    <p><b>Resturants</b></p>
                                </center>
                            </a>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <a href="#" class="thumbnail">
                                <img src="assets/system/img/landing/coffee.png" alt="...">
                                <center>
                                    <p><b>Coffee</b></p>
                                </center>
                            </a>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <a href="#" class="thumbnail">
                                <img src="assets/system/img/icon_industry_more.svg" alt="...">
                                <center>
                                    <p><b>More Business Types</b></p>
                                </center>
                            </a>
                        </div>

                    </div>-->
                </div>
            </div>
        </div>
    </section>
    <section id="testimonials" class="navy-section testimonials" style="margin-top: 0">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center wow zoomIn">
                    <h2>
                    Download demo version of FlexApp
                </h2>
                    <h3>Get Your App Now</h3>
                    <p>This is Photoshop's version of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis bibendum auctor, nisi elit consequat ipsum, nec sagittis sem nibh id elit. Duis sed odio sit amet nibh vulputate cursus a sit amet mauris. Morbi accumsan ipsum velit. Nam nec tellus a odio tincidunt auctor a ornare odio. Sed non mauris vitae erat consequat auctor eu in elit. </p>
                    <div class="btn-row">
                        <center>
                            <a href="#" target="_blank" class="btn btn-theme ripple-effect btn-theme-md btn-theme-transparent">
                                <img src="assets/system/img/icon/appstore.png" width="122px" height="43px">
                            </a>
                            <a href="#" target="_blank" class="btn btn-theme ripple-effect btn-theme-md btn-theme-transparent">
                                <img src="assets/system/img/icon/playstore.png" width="122px" height="43px">
                            </a>
                            <a href="#" target="_blank" class="download-link">
                                <img src="assets/system/img/icon/windowstore.png" width="122px" height="43px">
                            </a>
                        </center>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class='gray-section'>
		<div class="row">
			<div class="col-lg-12 text-center">
				<div class="navy-line"></div>
				<h1><strong>Software Benifits</strong></h1>
				<p>What's you can benifitted from our System.</p>
			</div>
		</div>
		<div class='row'>
			<div class='col-sm-5 col-sm-offset-1 benifit'>
				<ul list-style-type='square'>
					<li>Easy to navigate, and is compatible with touch screen terminals and barcode scanners</li>
					<li>We are provide Bug free, Error free, Virus free software. </li>
					<li>Fast page loading facility.</li>
					<li>Instatnt update facility</li>
					<li>Responsive- Mobile, Tablet, Laptop</li>
					<li>24/7 Hours Customer Support.</li>
                    <li>100% Customer Satisfaction.</li>
					<li>Smart Product Arrangement.</li>
				</ul>
			</div>
			<div class='col-sm-6'>
			<img src='assets/system/img/hardware.png'>
			</div>
		</div>
    </section>
    <section id="testimonials" class="navy-section testimonials" style="margin-top: 0">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center wow zoomIn">
                    <i class="fa fa-comment big-icon"></i>
                    <h1>
                    SUBSCRIBE
                </h1>
                    <div class="input-group col-md-offset-2 col-md-8">
                        <input type="email" class="form-control" placeholder="Enter your email">
                        <span class="input-group-btn">
							<button class="btn btn-theme" type="submit">Subscribe</button>
						</span>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <?php include dirname(__FILE__) .'/includes/footer.php'; ?>