<?php defined('_AZ') or die('Restricted access'); 
	include dirname(__FILE__) .'/includes/header.php';
	if (app('login')->isLoggedIn()) {
		redirect("/verify");
	}
	
?>

<style>
.block-details label{font-size:14px;font-weight:400;}
.block-details label i{font-size:18px; color:#1ab394;}
</style>

    <!--LOGIN-SIGNUP START -->
    <section class="term-section" id="page-top">
       <div class="container">
		 <div class="row">
            <div class="col-lg-12 text-center">
                    <div class="navy-line"></div>
                    <h1><strong>AppsOwl Terms of Use</strong></h1>
                    <p></p>
            </div>
        </div>
	   </div>
    </section>
	
	<section class="white-section">
		<div class="container">
			<?php  
				$getTerms = app('admin')->getwhereid('as_terms_and_condition','type','t&c');
				echo $getTerms['body_text'];
			?>
		</div>
	</section>

    <?php include dirname(__FILE__) .'/includes/footer.php'; ?>