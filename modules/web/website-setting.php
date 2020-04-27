<?php defined('_AZ') or die('Restricted access'); 
include dirname(__FILE__) .'/include/header.php';
include dirname(__FILE__) .'/include/side_bar.php';
include dirname(__FILE__) .'/include/navbar.php';
?>
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-4">
            
                <h2><?php echo trans('website_setting'); ?></h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('/') }}"><?php echo trans('dashboard'); ?></a>
                    </li>
                    <li class="active">
                        <strong><?php echo trans('website_setting'); ?></strong>
                    </li>
                </ol>
        </div>
        <div class="col-lg-8">
            <div class="title-action">

            </div>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">

        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                <i class="fa fa-wrench"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-user">
                                <li><a href="#">Config option 1</a>
                                </li>
                                <li><a href="#">Config option 2</a>
                                </li>
                            </ul>
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <form method="post" class="form-horizontal" id="dropzoneForm" enctype="multipart/form-data">
                            <!-- id="dropzoneForm" -->

                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo trans('website_name'); ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="website_name" id="website_name" class="form-control">
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo trans('website_title'); ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="website_title" id="website_title" class="form-control">
                                </div>
                            </div>

                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo trans('website_short_description'); ?></label>
                                <div class="col-sm-10" id="short_desc">

                                    <textarea class="form-control" name="short_desc" data-provide="markdown">

                                    </textarea>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo trans('about_us'); ?></label>
                                <div class="col-sm-10" id="about_us">

                                    <textarea class="form-control" name="about_us" data-provide="markdown">
                                    </textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo trans('contact'); ?>
                                    <br>
                                    <small class="text-navy"><?php echo trans('enter_contact_info'); ?></small>
                                </label>
                                <div class="col-sm-10">

                                    <div class="col-lg-12">
                                        <div class="ibox float-e-margins">
                                            <div class="ibox-content">
                                                <div class="row">
                                                    <div class="col-sm-6 b-r">
                                                        <div class="form-group">
                                                            <label class="col-lg-2 control-label"><?php echo trans('name'); ?></label>

                                                            <div class="col-lg-10">
                                                                <input type="text" name="contact_name" placeholder="<?php echo trans('name'); ?>" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-lg-2 control-label"><?php echo trans('phone'); ?></label>

                                                            <div class="col-lg-10">
                                                                <input type="text" name="contact_phone" placeholder="<?php echo trans('phone'); ?>" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label class="col-lg-2 control-label"><?php echo trans('email'); ?></label>

                                                            <div class="col-lg-10">
                                                                <input type="email" name="contact_email" placeholder="<?php echo trans('email'); ?>" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-lg-2 control-label"><?php echo trans('your_message'); ?></label>

                                                            <div class="col-lg-10">
                                                                <input type="text" name="contact_message" placeholder="<?php echo trans('your_message'); ?>" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo trans('address'); ?></label>
                                <div class="col-sm-10" name="contact" id="contact">

                                    <textarea class="form-control" name="address" data-provide="markdown">

                                    </textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo trans('social_media'); ?>
                                    <br>
                                    <small class="text-navy"><?php echo trans('select_your_media'); ?></small></label>
                                <div class="col-sm-10">
                                    <div class="col-lg-12">
                                        <div class="ibox float-e-margins">
                                            <div class="ibox-content">
                                                <div class="row">
                                                    <div class="col-sm-6 b-r">
                                                        <div class="form-group">
                                                            <label class="col-lg-2 control-label"><?php echo trans('facebook'); ?></label>

                                                            <div class="col-lg-10">
                                                                <input type="text" name="fb_url" placeholder="<?php echo trans('facebook_url'); ?>" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-lg-2 control-label"><?php echo trans('twitter'); ?></label>

                                                            <div class="col-lg-10">
                                                                <input type="text" name="tw_url" placeholder="<?php echo trans('twitter_url'); ?>" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-lg-2 control-label"><?php echo trans('linkedin'); ?></label>

                                                            <div class="col-lg-10">
                                                                <input type="text" name="ln_url" placeholder="<?php echo trans('linkedin_url'); ?>" class="form-control">
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label class="col-lg-2 control-label"><?php echo trans('pinterest'); ?></label>

                                                            <div class="col-lg-10">
                                                                <input type="email" name="pin_url" placeholder="<?php echo trans('pinterest_url'); ?>" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-lg-2 control-label"><?php echo trans('instagram'); ?></label>

                                                            <div class="col-lg-10">
                                                                <input type="text" name="ins_url" placeholder="<?php echo trans('instagram_url'); ?>" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-lg-2 control-label"><?php echo trans('google_plus'); ?></label>

                                                            <div class="col-lg-10">
                                                                <input type="text" name="g_url" placeholder="<?php echo trans('google_plus_url'); ?>" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div>

                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <a href="admin/website-setting">
                                    <label class="btn btn-success" ><?php echo trans('cancel'); ?></label></a>
                                    <button class="btn btn-primary" type="submit"><?php echo trans('save_changes'); ?></button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include dirname(__FILE__) .'/include/footer.php'; ?>