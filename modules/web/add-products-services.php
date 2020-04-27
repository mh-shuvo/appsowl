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
                        <h2><?php echo trans('add_product_service'); ?></small></h2>
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
                        <form method="post" class="form-horizontal" id="dropzoneForm" enctype="multipart/form-data" ><!-- id="dropzoneForm" -->
                            
                            <div class="form-group"><label class="col-sm-2 control-label"><?php echo trans('choose_product_service'); ?></label>

                                    <div class="col-sm-2">
                                        <select class="form-control m-b" name="account">
                                        <option><?php echo trans('service'); ?></option>
                                        <option><?php echo trans('product'); ?></option>
                                    </select>
                                    </div>
                                </div>

                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo trans('title'); ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="portfolio_name" id="portfolio_name" class="form-control">
                                </div>
                            </div>

                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo trans('short_description'); ?></label>
                                <div class="col-sm-10" name="portfolio_desc" id="portfolio_desc">

                                    <textarea class="form-control" data-provide="markdown">

                                    </textarea>
                                </div>
                            </div>

                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Image (300x200)<?php echo trans('image_x'); ?></label>
                                <div class="col-sm-10 fallback">

                                    <input name="file" type="file" name="product_image" multiple />

                                </div>
                            </div>
                             <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo trans('price'); ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="old_price" id="old_price" class="form-control" placeholder="<?php echo trans('old_price'); ?>"><br>
                                    <input type="text" name="new_price" id="new_price" class="form-control" placeholder="<?php echo trans('new_price'); ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <a href="admin/products-services">
                                    <label class="btn btn-success" ><?php echo trans('cancel'); ?></label></a>
                                    <button type="submit" class="btn btn-primary"><?php echo trans('save_changes'); ?></button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include dirname(__FILE__) .'/include/footer.php'; ?>