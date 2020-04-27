<?php defined('_AZ') or die('Restricted access'); 
include dirname(__FILE__) .'/includes/header.php';?>
<section class="timeline" id="tutorial" style="background-color:#f9f9f9">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 text-center">
				<div class="navy-line"></div>
				<h1>Tutorial</h1>
				<p>Our tutorials help you how to use it. </p>
			</div>
		</div>
		<div class="row features-block">
			<div class="row pb-row">
				<?php $tutorials = app('admin')->getTutorials(); foreach($tutorials as $tutorial){ ?>
				<div class="col-md-3 pb-video">
					<iframe class="pb-video-frame" width="100%" height="230" src="https://youtube.com/embed/<?php echo $tutorial['link']; ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
					<label class="form-control label-warning text-center" height="auto"><?php echo $tutorial['title']; ?></label>
				</div>
				
				<?php } ?>
				
			</div>
		</div>
	</div>
	<!--<iframe width="1195" height="672" src="https://www.youtube.com/embed/gYEuuuMcKus" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>-->
</section>
<?php include dirname(__FILE__) .'/includes/footer.php'; ?>