<?php defined('_AZ') or die('Restricted access'); 
include dirname(__FILE__) .'/include/header.php';
include dirname(__FILE__) .'/include/side_bar.php';
include dirname(__FILE__) .'/include/navbar.php';
?>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-4">
 
    <h2><?php echo trans('template'); ?></h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{ url('/') }}"><?php echo trans('dashboard'); ?></a>
            </li>
            <li class="active">
                <strong><?php echo trans('template'); ?></strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-8">
        <div class="title-action">
            <a href="admin/add-client" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> <?php echo trans('add_template'); ?> </a>
        </div>
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">

                <div class="row">
                    <div class="col-lg-3">
                        <div class="ibox">
                            <div class="ibox-content containent">
                                <img class="active" alt="image" src="images/services/1.jpg" width="350px">
                                <div class="text-block">
                                    <a href="" data-toggle="modal" class="btn btn-primary btn-xs product_edit" style="font-size: 18px;">Active</a>       
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="ibox">
                            <div class="ibox-content ">
                                <a href="">
                               <img alt="image" src="images/services/1.jpg" width="350px"></a>

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="ibox">
                            <div class="ibox-content">
                                <img alt="image" src="images/services/1.jpg" width="350px">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                    <div class="ibox">
                        <div class="ibox-content">
                                <!-- <h5 class="m-b-md">Server status Q43</h5>
                                <h2 class="text-danger">
                                    <i class="fa fa-play fa-rotate-90"></i> Down
                                </h2>
                                <small>Server down since 4:32 pm.</small> -->
                            <img  alt="image" src="images/services/1.jpg" id="1" width="350px">
                        </div>
                    </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="ibox">
                            <div class="ibox-content">
                                <img alt="image" src="images/services/1.jpg" width="350px">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="ibox">
                            <div class="ibox-content">
                               <img alt="image" src="images/services/1.jpg" width="350px">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="ibox">
                            <div class="ibox-content">
                                <img alt="image" src="images/services/1.jpg" width="350px">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="ibox">
                            <div class="ibox-content">

                                <!-- <h5 class="m-b-md">Server status Q43</h5>
                                <h2 class="text-danger">
                                    <i class="fa fa-play fa-rotate-90"></i> Down
                                </h2>
                                <small>Server down since 4:32 pm.</small> -->
                                <img alt="image" src="images/services/1.jpg" width="350px">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
   <!--  <div class="wrapper wrapper-content">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">

                    <div class="ibox-content">

                        ==

                        <div class="lightBoxGallery">
                            <a href="assets/img/gallery/1.jpg" title="Image from Unsplash" data-gallery=""><img src="assets/img/gallery/1s.jpg"></a>
                            <a href="assets/img/gallery/2.jpg" title="Image from Unsplash" data-gallery=""><img src="assets/img/gallery/2s.jpg"></a>
                            <a href="assets/img/gallery/3.jpg" title="Image from Unsplash" data-gallery=""><img src="assets/img/gallery/3s.jpg"></a>
                            <a href="assets/img/gallery/4.jpg" title="Image from Unsplash" data-gallery=""><img src="assets/img/gallery/4s.jpg"></a>
                            <a href="assets/img/gallery/5.jpg" title="Image from Unsplash" data-gallery=""><img src="assets/img/gallery/5s.jpg"></a>
                            <a href="assets/img/gallery/6.jpg" title="Image from Unsplash" data-gallery=""><img src="assets/img/gallery/6s.jpg"></a>
                            <a href="assets/img/gallery/7.jpg" title="Image from Unsplash" data-gallery=""><img src="assets/img/gallery/7s.jpg"></a>
                            <a href="assets/img/gallery/8.jpg" title="Image from Unsplash" data-gallery=""><img src="assets/img/gallery/8s.jpg"></a>
                            <a href="assets/img/gallery/9.jpg" title="Image from Unsplash" data-gallery=""><img src="assets/img/gallery/9s.jpg"></a>
                       
                            <div id="blueimp-gallery" class="blueimp-gallery">
                                <div class="slides"></div>
                                <h3 class="title"></h3>
                                <a class="prev">‹</a>
                                <a class="next">›</a>
                                <a class="close">×</a>
                                <a class="play-pause"></a>
                                <ol class="indicator"></ol>
                            </div>

                        </div>

                    </div>
                </div>
            </div>

            </div>
        </div> -->
    <?php include dirname(__FILE__) .'/include/footer.php'; ?>