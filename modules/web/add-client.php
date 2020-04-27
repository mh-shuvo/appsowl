
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
                            <h2><?php echo trans('add_client'); ?></small></h2>
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

                            <form method="post" class="form-horizontal" id="dropzoneForm" enctype="multipart/form-data" >
                                
                                <div class="hr-line-dashed"></div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><?php echo trans('client_name'); ?></label>
                                         <div class="col-sm-10">
                                            <input type="text" name="name" id="name" class="form-control">
                                         </div>
                                </div>
                            
                               <div class="hr-line-dashed"></div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><?php echo trans('client_logo'); ?></label>
                                         <div class="col-sm-10">
                                             <input name="file" type="file" name="logo" multiple />
                                         </div>
                                </div>
                                 <div class="hr-line-dashed"></div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><?php echo trans('url'); ?></label>
                                         <div class="col-sm-10">
                                            <input type="text" name="url" id="url" class="form-control">
                                         </div>
                                </div>
                                
                                <div class="hr-line-dashed"></div>
                                <div class="form-group">
                                    <div class="col-sm-4 col-sm-offset-2">
                                        <a href="admin/client">
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