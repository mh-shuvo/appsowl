<!DOCTYPE html>
<html dir="ltr" lang="en-US">
	<!-- Head
	============================================= -->
	<?php include('include/head.php'); ?>
	
	<!-- Body
	============================================= -->
    <body class="stretched" data-loader="11" data-loader-color="#543456">
		
        <!-- Document Wrapper
		============================================= -->
        <div id="wrapper" class="clearfix">
			
            <!-- Header
			============================================= -->
            <header id="header" class="full-header transparent-header border-full-header dark static-sticky" data-sticky-class="not-dark" data-sticky-offset="full" data-sticky-offset-negative="100">
				
                <?php 
					//include('include/header.php'); 
				?>
				
			</header>
            <!-- #header end -->
			
            <!-- Slider Content Section | This file contain in | section/slider.php
			============================================= -->
			<?php 
				//include('section/slider-three.php'); 
			?>
			<?php 
				include('section/login.php'); 
			?>
			
            <!-- #slider end -->
			
            <!-- Content
			============================================= -->
            <section id="content">
                <div class="content-wrap nopadding">
					<?php 
						//include('section/works.php'); 
						//include('section/services.php'); 
						//include('section/service-product.php'); 
						//include('section/about.php'); 
						//include('section/contact.php'); 
					?>
				</div>
			</section>
            <!-- #content end -->
            <!-- Footer
			============================================= -->
            <?php 
				//include('include/footer.php'); 
			?>
		</div>
        <!-- #wrapper end -->
        <!-- Go To Top
		============================================= -->
        <div id="gotoTop" class="icon-angle-up"></div>
		
        <?php 
			include('include/footer_link.php'); 
		?>
		
	</body>
	
</html>