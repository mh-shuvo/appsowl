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
        <div class="row justify-content-center gutter-xs-none sg-pricing-cards align-items-stretch m-b-lg">
		<?php 
			$softwareServices = app('db')->table('as_software_variation')->where("software_variation_type", "global")->get();
			foreach($softwareServices as $softwareService){
			$subscribe_soft = app('db')->table('as_subscribe')->where("software_variation_id", $softwareService['software_variation_id'])->get(1);
		?>
            <div class="col-lg-3 col-6 card sg-pricing-card <?php if($subscribe_soft['subscribe_status'] =='active') { echo "sg-pricing-card-center"; } else{ echo "sg-pricing-card-side"; } ; ?>" data-plan="<?php echo $softwareService['software_variation_name']; ?>">
                <div class="sg-pricing-video-section">
				
                  <?php 
					if($softwareService['software_manage_store'] == '1'){
				  ?>
                    <img src="https://d30fzr2520l6g5.cloudfront.net/corp-images/pricing/plan-store_lite.svg" alt="Enterprise store icon" class="d-block d-lg-none w-100">
					
					<?php } else if($softwareService['software_manage_store'] == '1' && $softwareService['software_variation_name'] == 'Standard'){ ?>
					
					<img src="https://d30fzr2520l6g5.cloudfront.net/corp-images/pricing/plan-store_pro.svg" alt="Enterprise store icon" class="d-block d-lg-none w-100">
					
					<?php } else if($softwareService['software_manage_store'] >= '2'){ ?>
					
						<img src="https://d30fzr2520l6g5.cloudfront.net/corp-images/pricing/plan-store_enterprise.svg" alt="Enterprise store icon" class="d-block d-lg-none w-100">
						
					<?php }?>
					
					
                    <div class="sg-pricing-video-content">
                        <h3><?php echo $softwareService['software_variation_name']; ?></h3>
                        <p>Large multi-store retailers or franchises</p>
                    </div>
                </div>
                <div class="sg-pricing-price-section">
                    
					  <div class="sg-pricing-price">
                        <span class="sg-pricing-currency">Tk</span><strong><?php echo $softwareService['software_variation_price']; ?></strong><span class="sg-pricing-monthly">/mo</span>
                    </div>
                    <hr>
                    <div class="sg-pricing-plan">
                        <ul>
                            <li><?php echo $softwareService['software_manage_store'];?> Outlets</li>
                            <li>
                                Unlimited Registers
                            </li>
                            <li>Unlimited turnover</li>
                        </ul>
                    </div>
                </div>
                <div class="sg-pricing-features-section">
                    
                    <div class="d-none d-lg-block">
                        <div class="sg-pricing-features">
							<?php echo $softwareService['software_variation_featured_list']; ?>
                            <div class="sg-pricing-features-button">
							
							<a class="btn btn-secondary btn-block" href="/">Get Started</a>
							
                            </div>
                        </div>
                    </div>
                </div>
            </div>
			<?php } ?>
			<div class="col-lg-3 col-6 card sg-pricing-card" data-plan="Custom">
                <div class="sg-pricing-video-section">
                  
					
						<img src="https://d30fzr2520l6g5.cloudfront.net/corp-images/pricing/plan-store_enterprise.svg" alt="Enterprise store icon" class="d-block d-lg-none w-100">
						
				
					
					
                    <div class="sg-pricing-video-content">
                        <h3>Custom</h3>
                        <p>Large multi-store retailers or franchises</p>
                    </div>
                </div>
                <div class="sg-pricing-price-section">
                    
					  <div class="sg-pricing-price">
                        <span class="sg-pricing-currency" style="margin-top:15px;">
                            <a class="btn btn-default btn-sm" href="tel:+8801844519432">Contact Us</a>
                        </span>
                    </div>
                    <hr>
                    <div class="sg-pricing-plan">
                        <ul>
                            <li>Custom Outlets</li>
                            <li>
                                Unlimited Registers
                            </li>
                            <li>Unlimited turnover</li>
                        </ul>
                    </div>
                </div>
                <div class="sg-pricing-features-section">
                    
                    <div class="d-none d-lg-block">
                        <div class="sg-pricing-features">
                            <div class="sg-pricing-features-button">
							<dl class="list list--single-line list--tick--green ">
							    <dd>Custom requirement</dd>
							</dl>
							<a class="btn btn-secondary btn-block" href="tel:+8801844519435">Contact Us</a>
							
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</section>
<?php include dirname(__FILE__) .'/includes/footer.php'; ?>