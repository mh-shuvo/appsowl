<!-- Go To Top
============================================= -->
<div id="gotoTop" class="icon-angle-up"></div>

<!-- External JavaScripts
============================================= -->
<script src="assets/lp/js/jquery.js"></script>
<script src="assets/lp/js/plugins.js"></script>

<!-- Google Map JavaScripts
============================================= -->
<script src="https://maps.google.com/maps/api/js?key=YOUR_API_KEY"></script>
<script src="assets/lp/js/jquery.gmap.js"></script>

<!-- Footer Scripts
============================================= -->
<script src="assets/lp/js/functions.js"></script>

<script>
	
	jQuery(window).on( 'load', function(){
		
		// Google Map
		jQuery('#headquarters-map').gMap({
			address: 'Melbourne, Australia',
			maptype: 'ROADMAP',
			zoom: 14,
			markers: [
			{
				address: "Melbourne, Australia",
				html: "Melbourne, Australia",
				icon: {
					image: "assets/lp/images/icons/map-icon-red.png",
					iconsize: [32, 32],
					iconanchor: [14,44]
				}
			}
			],
			doubleclickzoom: false,
			controls: {
				panControl: false,
				zoomControl: false,
				mapTypeControl: false,
				scaleControl: false,
				streetViewControl: false,
				overviewMapControl: false
			},
			styles: [{"featureType":"all","elementType":"labels.text.fill","stylers":[{"saturation":36},{"color":"#000000"},{"lightness":40}]},{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#000000"},{"lightness":16}]},{"featureType":"all","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#000000"},{"lightness":20}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"lightness":17},{"weight":1.2}]},{"featureType":"administrative","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"administrative.country","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"administrative.country","elementType":"geometry","stylers":[{"visibility":"simplified"}]},{"featureType":"administrative.country","elementType":"labels.text","stylers":[{"visibility":"simplified"}]},{"featureType":"administrative.province","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"administrative.locality","elementType":"all","stylers":[{"visibility":"simplified"},{"saturation":"-100"},{"lightness":"30"}]},{"featureType":"administrative.neighborhood","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"administrative.land_parcel","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"landscape","elementType":"all","stylers":[{"visibility":"simplified"},{"gamma":"0.00"},{"lightness":"74"}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":20}]},{"featureType":"landscape.man_made","elementType":"all","stylers":[{"lightness":"3"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":21}]},{"featureType":"road","elementType":"geometry","stylers":[{"visibility":"simplified"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#000000"},{"lightness":17}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"lightness":29},{"weight":0.2}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":18}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":16}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":19}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":17}]}]
		});
		
	});
	
</script>
<script src="assets/js/vendor/sha512.js"></script>
<script src="assets/js/vendor/popper.min.js"></script>
<!--<script src="assets/js/vendor/bootstrap.min.js"></script>-->
<script src="assets/js/vendor/jquery-validate/jquery.validate.min.js"></script>
<script src="assets/js/app/bootstrap.php"></script>
<script src="assets/js/app/common.js"></script>
<?php if (ASLang::getLanguage() != DEFAULT_LANGUAGE) : ?>
<script src="assets/js/vendor/jquery-validate/localization/messages_<?= ASLang::getLanguage() ?>.js"></script>
<?php endif; ?>

<!-- Page Scripts -->
<script type="text/javascript" src="assets/js/app/login.js"></script>
<script type="text/javascript" src="assets/js/app/passwordreset.js"></script>
<script src="assets/system/js/plugins/sweetalert/sweetalert.min.js"></script>
