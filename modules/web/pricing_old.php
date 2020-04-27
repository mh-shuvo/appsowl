<?php defined('_AZ') or die('Restricted access'); 
include dirname(__FILE__) .'/includes/header.php';
?>
[header]
	<?php 
		getCss('assets/css/v_pricing.css');
	?>
[/header]
<section id="pricing" class="pricing gray-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="row m-b-lg">
				<div class="col-lg-12 text-center">
					<div class="navy-line"></div>
					<h1>App Pricing</h1>
					<p>Donec sed odio dui. Etiam porta sem malesuada magna mollis euismod.</p>
				</div>
			</div>
        </div>
        <div class="row justify-content-center gutter-xs-none sg-pricing-cards align-items-stretch">
		<?php 
			$softwareServices = app('db')->table('as_software_variation')->where("software_variation_type", "global")->get();
			foreach($softwareServices as $softwareService){
		?>
            <div class="col-lg-4 col-12 card sg-pricing-card sg-pricing-card-side" data-plan="lite">
                <div class="sg-pricing-video-section">
                    
                    <img src="https://d30fzr2520l6g5.cloudfront.net/corp-images/pricing/plan-store_lite.svg" alt="Small store icon" class="d-block d-lg-none w-100">
                    <div class="sg-pricing-video-content">
                        <h3>Lite</h3>
                        <p>Small retailers with basic operations</p>
                    </div>
                </div>
                <div class="sg-pricing-price-section">
                    <div class="sg-pricing-price">
                        <span class="sg-pricing-currency">$</span><strong>99</strong><span class="sg-pricing-monthly">/mo</span>
                        <p>
                            USD billed annually
                            <br> or $119 billed monthly
                        </p>
                    </div>
                    <hr>
                    <div class="sg-pricing-plan">
                        <ul>
                            <li>1 Outlet</li>
                            <li>
                                1+ Registers
                                <div class="sg-pricing-hover-info">
                                    <img src="https://d30fzr2520l6g5.cloudfront.net/corp-images/pricing/icon-25_info.svg" alt="Plus Icon">
                                    <p>
                                        A register is a selling station in Vend. All plans include one register per outlet. Extra registers can be added for $49/mo billed annually per register (or $59/mo billed monthly).
                                    </p>
                                </div>
                            </li>
                            <li>
                                $20k monthly turnover in USD
                                <div class="sg-pricing-hover-info">
                                    <img src="https://d30fzr2520l6g5.cloudfront.net/corp-images/pricing/icon-25_info.svg" alt="Plus Icon">
                                    <p>
                                        Turnover limits are set in selected trading currencies otherwise USD. If you exceed your turnover limit three times in a 12 month period, you’ll need to upgrade to our Pro plan.
                                    </p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="sg-pricing-features-section">
                    <div class="sg-pricing-features-header h4">
                        Intuitive point of sale and basic store management tools
                    </div>
                    <div class="d-none d-lg-block">
                        <div class="sg-pricing-features">
                            <dl class="list list--single-line list--tick--green">
                                <dd>Intuitive Point of Sale</dd>
                                <dd>Real-time Inventory Management</dd>
                                <dd>24/7 Phone &amp; Online Support</dd>
                                <dd>Small Business Reporting</dd>
                                <dd>Xero Accounting Add-On</dd>
                            </dl>
                            <div class="sg-pricing-features-button">
                                <a href="https://secure.vendhq.com/signup?plan=lite" class="btn btn-secondary btn-block">Get started</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12 card sg-pricing-card sg-pricing-card-side" data-plan="pro">
                <div class="sg-pricing-video-section">
                  
                    <img src="https://d30fzr2520l6g5.cloudfront.net/corp-images/pricing/plan-store_pro.svg" alt="Professional store icon" class="d-block d-lg-none w-100">
                    <div class="sg-pricing-video-content">
                        <h3>Pro</h3>
                        <p>Established single or multi-store retailers</p>
                    </div>
                </div>
                <div class="sg-pricing-price-section">
                    <div class="sg-pricing-price">
                        <span class="sg-pricing-currency">$</span><strong>129</strong><span class="sg-pricing-monthly">/mo</span>
                        <p>
                            USD billed annually
                            <br> or $159 billed monthly
                        </p>
                    </div>
                    <hr class="sg-pricing-per-outlet">
                    <div class="sg-pricing-plan">
                        <ul>
                            <li>1+ Outlets</li>
                            <li>
                                1+ Registers
                                <div class="sg-pricing-hover-info">
                                    <img src="https://d30fzr2520l6g5.cloudfront.net/corp-images/pricing/icon-25_info.svg" alt="Plus Icon">
                                    <p>
                                        A register is a selling station in Vend. All plans include one register per outlet. Extra registers can be added for $49/mo billed annually per register (or $59/mo billed monthly).
                                    </p>
                                </div>
                            </li>
                            <li>Unlimited turnover</li>
                        </ul>
                    </div>
                </div>
                <div class="sg-pricing-features-section">
                    <div class="sg-pricing-features-header h4">
                        The complete platform to manage operations &amp; grow sales
                    </div>
                    <div class="d-none d-lg-block">
                        <div class="sg-pricing-features">
                            <dl class="list list--single-line list--tick--green ">
                                <dd>Intuitive Point of Sale</dd>
                                <dd>Real-time Inventory Management</dd>
                                <dd>24/7 Phone &amp; Online Support</dd>
                                <dd>Advanced Reporting &amp; Analytics</dd>
                                <dd>Advanced Promotions &amp; Gift Cards</dd>
                                <dd>All Add-Ons &amp; Ecommerce Channels</dd>
                                <dd>API Access</dd>
                                <dd>Multi-Outlet Retail Management</dd>
                            </dl>
                            <div class="sg-pricing-features-button">
                                <a href="https://secure.vendhq.com/signup?plan=pro" class="btn btn-secondary btn-block">Get started</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12 card sg-pricing-card sg-pricing-card-side" data-plan="enterprise">
                <div class="sg-pricing-video-section">
                  
                    <img src="https://d30fzr2520l6g5.cloudfront.net/corp-images/pricing/plan-store_enterprise.svg" alt="Enterprise store icon" class="d-block d-lg-none w-100">
                    <div class="sg-pricing-video-content">
                        <h3>Enterprise</h3>
                        <p>Large multi-store retailers or franchises</p>
                    </div>
                </div>
                <div class="sg-pricing-price-section">
                    <div class="sg-pricing-price">
                        <h3 class="text-white">Get a quote</h3>
                        <p>
                            <a href="/contact-sales" class="btn btn-outline-inverse">TALK TO US</a>
                        </p>
                    </div>
                    <hr>
                    <div class="sg-pricing-plan">
                        <ul>
                            <li>6+ Outlets</li>
                            <li>
                                1+ Registers
                                <div class="sg-pricing-hover-info">
                                    <img src="https://d30fzr2520l6g5.cloudfront.net/corp-images/pricing/icon-25_info.svg" alt="Plus Icon">
                                    <p>
                                        A register is a selling station in Vend. All plans include one register per outlet. Extra registers can be added.
                                    </p>
                                </div>
                            </li>
                            <li>Unlimited turnover</li>
                        </ul>
                    </div>
                </div>
                <div class="sg-pricing-features-section">
                    <div class="sg-pricing-features-header h4">
                        A tailored solution supporting multi-store operations
                    </div>
                    <div class="d-none d-lg-block">
                        <div class="sg-pricing-features">
                            <strong>All features in the Pro plan plus:</strong>
                            <dl class="list list--single-line list--tick--green ">
                                <dd>Dedicated Account Manager</dd>
                                <dd>
                                    Customized Onboarding
                                    <div class="sg-pricing-hover-info">
                                        <img src="https://d30fzr2520l6g5.cloudfront.net/corp-images/pricing/icon-25_info.svg" alt="Plus Icon">
                                        <p>
                                            A customized onboarding package is a required add-on for all Enterprise customers, either direct with the Vend team or with one of our Expert Partners.
                                        </p>
                                    </div>
                                </dd>
                            </dl>
                            <div class="sg-pricing-features-button">
                                <a href="/contact-sales" class="btn btn-secondary btn-block">Get a quote</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!--section id="pricing" class="pricing gray-section">
    <div class="container">
        <div class="row m-b-lg">
            <div class="col-lg-12 text-center">
                <div class="navy-line"></div>
                <h1>Our Pricing</h1>
                <p></p>
            </div>
        </div>
		<!--div class="row">
			<div class="col-sm-12">
			 <div class="ibox ibox_category">
                    <div class="ibox-content landing-page">
						<section id="pricing" class="pricing">
							<div class="row m-b-lg">
								<div class="col-lg-12 text-center">
									<div class="navy-line"></div>
									<h1>App Pricing</h1>
									<p>Donec sed odio dui. Etiam porta sem malesuada magna mollis euismod.</p>
								</div>
							</div>
							<div class="row">
								<?php 
									$softwareServices = app('db')->table('as_software_variation')->where("software_variation_type", "global")->get();
									foreach($softwareServices as $softwareService){
									?>
									<div class="col-lg-4 wow zoomIn">
										<ul class="pricing-plan list-unstyled <?php if($softwareService['is_featured']) echo "selected"; ?>">
											<li class="pricing-title">
												<?php echo $softwareService['software_variation_name']; ?>
											</li>
											<li class="pricing-desc">
												<?php echo $softwareService['software_variation_des']; ?>
											</li>
											<li class="pricing-price">
												<span><?php echo $softwareService['software_variation_price']; ?></span> / month
											</li>
											<?php echo $softwareService['software_variation_featured_list']; ?>
											<li>
												<a class="btn btn-primary btn-xs" 
												software_variation_name="<?php echo $softwareService['software_variation_name']; ?>" 
												software_variation_id="<?php echo $softwareService['software_variation_id']; ?>" 
												software_id="<?php echo $softwareService['software_id']; ?>" 
												software_variation_price="<?php echo $softwareService['software_variation_price']; ?>" 
												software_setup_fee="<?php echo $softwareService['software_setup_fee']; ?>" 
												href="/">
												Get Started</a>
											</li>
										</ul>
									</div>
								<?php  } ?>
							</div>
						</section>
					</div>
				</div>
			</div>
		</div-->
        <!--div class="row">
            <div class="col-lg-4 wow zoomIn">
                <ul class="pricing-plan list-unstyled">
                    <li class="pricing-title">
                       Barcode Scanner
                    </li>
                    <li class="pricing-desc">
                        <img src="assets/system/img/product/scanner.png" >
                    </li>
                    <li class="pricing-price">
                        <span>2000TK - 10,000Tk</span> / One Time
                    </li>
                    <div class="pricing-details">
                    <li>
                       <i class="fa fa-check-circle"></i> 32 Bit Color Depth
                    </li>
                    <li>
                      <i class="fa fa-check-circle"></i>  USB Supported
                    </li>
                    <li>
                      <i class="fa fa-check-circle"></i>  200 Scans/sec
                    </li>
                    <li>
                       <i class="fa fa-check-circle"></i> A4 Scan Breadth 
                    </li>
                  </div>
                    <li>
                        <a class="btn btn-primary btn-xs" href="#">Get Started</a>
                    </li>
                </ul>
            </div>
		
            <div class="col-lg-4 col-lg-offset-4 wow zoomIn">
                <ul class="pricing-plan list-unstyled selected">
                    <li class="pricing-title">
                       Poin of Sale(POS)
                    </li>
                    <li class="pricing-qoutation">
                       Established single or multi-store retailers
                    </li>
                    <li class="pricing-desc">
                      <img src="assets/system/img/product/pos_homepage.png" >
                    </li>
                    <li class="pricing-price">
                        <span>500 TK</span> / monthly
                    </li>
                    <div class="pricing-details">
                          <li>
                            <i class="fa fa-check-circle"></i> Intuitive Point of Sale
                        </li>
                        <li>
                           <i class="fa fa-check-circle"></i> Real-time Inventory Management
                        </li>
                        <li>
                           <i class="fa fa-check-circle"></i> 24/7 Phone & Online Support
                        </li>
                        <li>
                           <i class="fa fa-check-circle"></i>  Multi-Outlet Retail Management
                        </li>
                        <li>
                            <i class="fa fa-check-circle"></i> All Add-Ons & Ecommerce Channels
                        </li>
                       <!--  <li>
                            <strong>Support platform</strong>
                        </li>>
                    </div>
                    <li class="plan-action">
                        <a class="btn btn-primary btn-xs" href="#">Get Started</a>
                    </li>
                </ul>
            </div>
			


          <div class="col-lg-4 wow zoomIn">
                <ul class="pricing-plan list-unstyled">
                    <li class="pricing-title">
                       POS Printer
                    </li>
                    <li class="pricing-desc">
                        <img src="assets/system/img/product/printer.png" >
                    </li>
                    <li class="pricing-price">
                        <span>4,000Tk - 12,000 Tk</span> / One Time
                    </li>
                    <div class="pricing-details">
                    <li>
                       <i class="fa fa-check-circle"></i> 80mm Printer
                    </li>
                    <li>
                        <i class="fa fa-check-circle"></i> Thermal Receipt
                    </li>
                    <li>
                        <i class="fa fa-check-circle"></i> Auto Cut Pos 
                    </li>
                    <li>
                      <i class="fa fa-check-circle"></i> USB,Serial,Ethernet 3input together
                    </li>
                  </div>
                    <li>
                        <a class="btn btn-primary btn-xs" href="#">Get Started</a>
                    </li>
                </ul>
            </div> 
			
        </div-->
		  <!--<div class="row">
			<div class="col-lg-4 wow zoomIn">
                <ul class="pricing-plan list-unstyled">
                    <li class="pricing-title">
                     Cash register POS Drawer
                    </li>
                    <li class="pricing-desc">
                        <img src="assets/system/img/product/cash_drawer.jpg" >
                    </li>
                    <li class="pricing-price">
                        <span>4,000Tk - 6,000 Tk</span> / One Time
                    </li>
                    <div class="pricing-details">
                    <li>
                       <i class="fa fa-check-circle"></i>  Nine Ming JM-420N
                    </li>
                    <li>
                        <i class="fa fa-check-circle"></i> Driver interface RJ11 / SERIAL PORT
                    </li>
                    <li>
                        <i class="fa fa-check-circle"></i> Drive mode compatible with ESC / POS 
                    </li>
                    <li>
                      <i class="fa fa-check-circle"></i> Driving pulse voltage 9-33V 
                    </li> 
					<li>
                      <i class="fa fa-check-circle"></i> Panel material hard plastic 
                    </li>
					<li>
                      <i class="fa fa-check-circle"></i>Locking third gear four-function mode  
                    </li>
                  </div>
                    <li>
                        <a class="btn btn-primary btn-xs" href="#">Get Started</a>
                    </li>
                </ul>
            </div>
			<div class="col-lg-4 wow zoomIn">
                <ul class="pricing-plan list-unstyled ">
                    <li class="pricing-title">
                     Monitor
                    </li>
                    <li class="pricing-desc">
                        <img src="assets/system/img/product/monitor.png" >
                    </li>
                    <li class="pricing-price">
                        <span>4,000Tk - 10,000 Tk</span> / One Time
                    </li>
                    <div class="pricing-details">
                    <li>
                       <i class="fa fa-check-circle"></i>  Display: 38cm (15") to 80cm (31.5")
                    </li>
                    <li>
                        <i class="fa fa-check-circle"></i> OS: Win 10 / Linux . Android only with BMxM models
                    </li>
                    <li>
                        <i class="fa fa-check-circle"></i> Processor: Intel Celeron. Optional i3 / i5 / i7
                    </li>
                    <li>
                      <i class="fa fa-check-circle"></i> RAM: 4 GB or higher
                    </li> 
					<li>
                      <i class="fa fa-check-circle"></i> Bar code reader (built-in)
                    </li>
					<li>
                      <i class="fa fa-check-circle"></i>POS Software
                    </li>
                  </div>
                    <li>
                        <a class="btn btn-primary btn-xs" href="#">Get Started</a>
                    </li>
                </ul>
            </div>
			<div class="col-lg-4 wow zoomIn">
                <ul class="pricing-plan list-unstyled">
                    <li class="pricing-title">
                     POS Tablet
                    </li>
                    <li class="pricing-desc">
                        <img src="assets/system/img/product/pos_tablet.png" >
                    </li>
                    <li class="pricing-price">
                        <span>9,000Tk - 25,000 Tk</span> / One Time
                    </li>
                    <div class="pricing-details">
                    <li>
                       <i class="fa fa-check-circle"></i>  Network	2G, 3G
                    </li>
                    <li>
                        <i class="fa fa-check-circle"></i> Body	219.4 x 126.8 x 9.4 millimeter, 367 grams
                    </li>
                    <li>
                        <i class="fa fa-check-circle"></i> Operating System	Android Nougat v7.0
                    </li>
                    <li>
                      <i class="fa fa-check-circle"></i> RAM :2 GB  ROM: 16 GB
                    </li> 
					<li>
                      <i class="fa fa-check-circle"></i> USB	MicroUSB v2.0, USB-on-the-go (OTG)
                    </li>
					<li>
                      <i class="fa fa-check-circle"></i>Bluetooth, GPS, A-GPS, MP3, MP4, Radio, GPRS, Edge, Multitouch, Loudspeaker, OTA
                    </li>
                  </div>
                    <li>
                        <a class="btn btn-primary btn-xs" href="#">Get Started</a>
                    </li>
                </ul>
            </div>
		</div>-->
    <!--     <div class="row m-t-lg">
            <div class="col-lg-8 col-lg-offset-2 text-center m-t-lg">
                <p>*Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. <span class="navy">Various versions</span>  have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p>
            </div>
        </div>>
    </div-->

</section>
<?php include dirname(__FILE__) .'/includes/footer.php'; ?>