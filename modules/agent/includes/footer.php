<!-- CONTACT START -->
<section id="contact" class="gray-section contact" >
    <div class="container">
        <div class="row m-b-lg">
            <div class="col-lg-12 text-center">
                <div class="navy-line"></div>
                <h1><strong>Contact Us</strong></h1>
            </div>
        </div>
        <div class="row m-b-lg">
            <div class="col-lg-3 col-lg-offset-3">
                <address>
                    <strong><span class="navy">Software Galaxy, Ltd.</span></strong><br/>
                    House#6, Road#10, Nikunjo-2<br/>
                    Dhaka 1230, Dhaka, Bangladesh<br/>
                    <strong>Phone:- +88 0167-802090</strong>
                </address>
            </div>
            <div class="col-lg-4">
                <p class="text-color">
                    Software helps you in digitalization and develop
                    your life.
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 text-center">
                <a href="mailto:test@email.com" class="btn btn-primary">Send us mail</a>
                <p class="m-t-sm">
                    Or follow us on social platform
                </p>
                <ul class="list-inline social-icon">
                    <li><a href=""><i class="fa fa-twitter"></i></a>
                    </li>
                    <li><a href="https://www.facebook.com/softwaregalaxyltd/" target="_blank"><i class="fa fa-facebook"></i></a>
                    </li>
                    <li><a href="#"><i class="fa fa-linkedin"></i></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 text-center m-t-lg m-b-lg">
                <p><strong>&copy; Software Galaxy Ltd.</strong><br/>Software helps you in digitalization and develop
                    your life.</p>
            </div>
        </div>
    </div>
</section>
<!-- CONTACT END -->
<!--<div class="row gray-section">
	<div class="col-lg-8 col-lg-offset-2 text-center m-t-lg m-b-lg">
		<p><strong>&copy; Software Galaxy Ltd.</strong><br/></p>
	</div>
</div>-->
<!-- Mainly scripts -->
<script src="assets/system/js/jquery-3.1.1.min.js"></script>
<script src="assets/system/js/bootstrap.min.js"></script>
<script src="assets/system/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="assets/system/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<!-- Custom and plugin javascript -->

<script>
$(document).ready(function () {
    // Handler for .ready() called.
    
});	
    // $(document).ready(function () {
        // $('#verify_form').hide();
		// $('.next_btn').hide();
		// $('.error').hide();
		// $('.success').hide();
        // $('body').scrollspy({
            // target: '.navbar-fixed-top',
            // offset: 80
		// });
		
		// $(".search_btn").click(function(){
			// event.preventDefault();
			// var domain=$("#domain").val()+'.appsowl.com';
			// alert(domain);
			// $.ajax({
				// headers: {
					// 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				// },
				// url:"/stream/user/domain_search",
				// method:"POST",
				// data:{domain:domain},
				// dataType:"json",
				// success:function(data){
					// if(data==1){
						// $('.next_btn').show();
						// $('.error').hide();
					// }
					// else{
						// $('.next_btn').hide();
						// $('.error').show();
					// }
				// }
				
			// }); 
		// });
		
	// });
	// $(window).load(function() {
	// 	$(".loader").fadeOut("slow",0.5);
	// });
	// $(function() {
	//    var duration = 120000; // it should finish in 120 seconds !
	// 	  var st = new Date().getTime();
	// 	  var interval = setInterval(function() {
	// 		var diff = Math.round(new Date().getTime() - st),
	// 		  val = Math.round(diff / duration * 100);
	// 		val = val > 100 ? 100 : val;
	// 		$("#dynamic").css("width", val + "%");
	// 		$("#dynamic").text(val + "%");
	// 		if (diff >= duration) {
	// 		  clearInterval(interval);
	// 		}
	// 	  }, 100);
	// 	  window.setTimeout(function () {
	// 			document.location.reload();
	// 		}, 120000);
		  
	// });
    // $('.register_btn').click(function(){
    //     $("#register_form").hide();
    //     $("#verify_form").show();
    //   })
    /*  $('.verify_btn').click(function(){
        $("#verify_form").hide();
        $("#register_form").show();
	})*/
	
</script>
<script src="assets/js/vendor/sha512.js"></script>
<script src="assets/js/vendor/jquery.min.js"></script>
<script src="assets/js/vendor/popper.min.js"></script>
<script src="assets/js/vendor/bootstrap.min.js"></script>
<script src="assets/js/vendor/jquery-validate/jquery.validate.min.js"></script>
<script src="assets/js/app/bootstrap.php"></script>
<script src="assets/js/app/common.js"></script>
<script src="assets/js/app/admin.js"></script>
<?php if (ASLang::getLanguage() != DEFAULT_LANGUAGE) : ?>
<script src="assets/js/vendor/jquery-validate/localization/messages_<?= ASLang::getLanguage() ?>.js"></script>
<?php endif; ?>

<!-- Page Scripts -->
<script type="text/javascript" src="assets/js/app/login.js"></script>
<script type="text/javascript" src="assets/js/app/register.js"></script>
<script type="text/javascript" src="assets/js/app/passwordreset.js"></script>
<script src="assets/system/js/plugins/sweetalert/sweetalert.min.js"></script>

</body>
</html>