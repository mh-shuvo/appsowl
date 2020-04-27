<!-- CONTACT START -->
<?php
$uriSegments = explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$page=$uriSegments[1];
// if(isset($this->route['page'])){
 // $page= $this->route['page'];echo $page;
// }
// else{$page='tst'; echo $page;}
 ?>
<section id="contact" class="<?php echo $page == 'forget-password'|| $page == 'pricing'|| $page == 'blog'|| $page == 'agent'|| $page == 'tutorial' || $page=='renew' ? 'white' : 'gray';?>-section contact" >
    <div class="container">
		<div class="col-lg-12 text-center">
                <div class="navy-line"></div>
                <h1><strong>Contact Us</strong></h1>
       </div>
	<div class="row">

        <!-- Grid column -->
        <div class="col-md-3 col-lg-4 col-xl-3 mb-4">
          <form>
            <div class="form-group">
              <label>Email</label>
              <input type="email" class="form-control" name="" placeholder="Enter Your Email">
            </div>
            <div class="form-group">
              <label>Message</label>
              <textarea class="form-control" placeholder="write your message in here." ></textarea>
            </div>
            <div class="form-group">
              <button class="btn btn-success btn-sm pull-right">Submit</button>
            </div>
          </form>

        </div>
        <!-- Grid column -->

        <!-- Grid column -->
        <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4">

          <!-- Links -->
          <h3 class="text-uppercase font-weight-bold">General Info</h3>

          <p>
            <a class="h5" href="/terms/">Terms and Use</a>
          </p>
          <p>
            <a class="h5" href="/privacy/">Privacy and Policy</a>
          </p>
          <p>
            <a class="h5" href="#!">About us</a>
          </p>
          <p>
            <a href="/agent-info/" target="_blank" class="h5">Agent Information</a>
          </p>
		  <p>
            <a href="agent" target="_blank" class="h5">Agent Registration</a>
          </p>

        </div>
        <!-- Grid column -->
		
        <!-- Grid column -->
        <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">

          <!-- Links -->
          <h3 class="text-uppercase font-weight-bold">Address</h3>
          
          <p>
            <i class="fa fa-home mr-3"></i>House#06, Road#10</p>
            <p>
              <i class="fa fa-map-marker"></i> Nikunja-02, Khilkhet, Dhaka-1229, Bangladesh
            </p>
          <p>
            <i class="fa fa-envelope mr-3"></i> <a href="mailto:softwaregalaxyltd@gmail.com">softwaregalaxyltd@gmail.com</a></p>
          <p>
            <i class="fa fa-phone mr-3"></i> <a href="callto:+8801844519430">+88 018 445 194 30-8</a></p>
          

        </div>
        <!-- Grid column -->
		
        <!-- Grid column -->
        <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4 social_links">
          <!-- Links -->
          <h3 class="text-uppercase font-weight-bold">Social links</h3>
    
          <p>
            <a class="h5" href="https://www.facebook.com/appsowl/" target="_blank"><i class='fa fa-facebook'></i> Facebook</a>
          </p>
		    <p>
            <a class="h5" href="https://appsowl.com/" target="_blank"><i class="fa fa-internet-explorer"></i> WebSite</a>
          </p>


        </div>
        <!-- Grid column -->

      </div>
    </div>
	<div class="row">
            <div class="col-lg-8 col-lg-offset-2 text-center m-t-lg m-b-lg">
                <p><strong><!-- &copy; -->All rights reserved APPSOWL.COM</strong><br/>Powered By: Software Galaxy Ltd.</p>
            </div>
        </div>
</section>



<?php
	echo getJs('https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js',false);
	echo getJs('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js',false);
	echo getJs('//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js',false,false);
	echo getJs('assets/system/js/plugins/metisMenu/jquery.metisMenu.js');
	echo getJs('assets/system/js/plugins/slimscroll/jquery.slimscroll.min.js',false);
	echo getJs('assets/js/vendor/sha512.js',false);
	echo getJs('assets/js/vendor/jquery.min.js',false);
	echo getJs('assets/js/vendor/popper.min.js',false);
	echo getJs('assets/js/vendor/bootstrap.min.js',false);
	echo getJs('assets/js/vendor/jquery-validate/jquery.validate.min.js',false);?>
	<script src="assets/js/app/bootstrap.php"></script>
	<?php
	echo getJs('assets/js/app/common.js');
	echo getJs('assets/js/app/admin.js');
	if (ASLang::getLanguage() != DEFAULT_LANGUAGE){
		echo getJs('assets/js/vendor/jquery-validate/localization/messages_'.ASLang::getLanguage().'.js');
	}
	
	echo getJs('assets/js/app/login.js');
	echo getJs('assets/js/app/register.js');
	echo getJs('assets/js/app/passwordreset.js');
	echo getJs('assets/system/js/plugins/sweetalert/sweetalert.min.js',false);
	
?>
<script>

</script>

</body>
</html>

