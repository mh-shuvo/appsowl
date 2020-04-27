<?php defined('_AZ') or die('Restricted access'); 
include dirname(__FILE__) .'/include/header.php';
include dirname(__FILE__) .'/include/side_bar.php';
include dirname(__FILE__) .'/include/navbar.php';
?>
    <div class="wrapper wrapper-content animated fadeInRight">

        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h2><?php echo trans('add_team'); ?></small></h2>
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

                        <form method="get" class="form-horizontal">
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo trans('team_name'); ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="name" id="name" class="form-control">
                                </div>
                            </div>

                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo trans('team_position'); ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="position" id="position" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo trans('image'); ?></label>

                                <div class="col-sm-10">
                                    <input name="file" type="file" name="portfolio_image" multiple />
                                </div>
                            </div>

                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo trans('social_media'); ?>
                                    <br>
                                    <small class="text-navy"><?php echo trans('select_your_media'); ?></small></label>
                                <div class="col-sm-10">
                                    <!-- <div class="row">
                                        <div class="col-md-12">
                                            <label>
                                                <input type="checkbox" checked="" value="option1" id="optionsRadios1" name="optionsRadios">
                                                <select class="form-control m-b" name="social1">
                                                    <option>Facebook</option>
                                                    <option>Twitter</option>
                                                    <option>Linkedin</option>
                                                    <option>Google +</option>
                                                    <option>Instagram</option>
                                                    <option>Pinterest</option>
                                                </select>
                                            </label>
                                            <input type="text" name="" placeholder="Your Social Url" class="form-control">
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>
                                                <input type="checkbox" value="option2" id="optionsRadios2" name="optionsRadios">
                                                <select class="form-control m-b" name="social2">
                                                    <option>Twitter</option>
                                                    <option>Facebook</option>
                                                    <option>Linkedin</option>
                                                    <option>Google +</option>
                                                    <option>Instagram</option>
                                                    <option>Pinterest</option>
                                                </select>
                                            </label>
                                            <input type="text" name="" placeholder="Your Social Url" class="form-control">
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>
                                                <input type="checkbox" value="option3" id="optionsRadios3" name="optionsRadios">
                                                <select class="form-control m-b" name="social3">
                                                    <option>Linkedin</option>
                                                    <option>Twitter</option>
                                                    <option>Facebook</option>
                                                    <option>Google +</option>
                                                    <option>Instagram</option>
                                                    <option>Pinterest</option>
                                                </select>
                                            </label>
                                            <input type="text" name="" placeholder="Your Social Url" class="form-control">
                                        </div> 
                                    </div> -->

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
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <a href="admin/team">
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