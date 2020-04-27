
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
                            <h2>Website Services</small></h2>
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
                                    <label class="col-sm-2 control-label">Service Name</label>
                                         <div class="col-sm-10">
                                            <input type="text" name="service_name" id="service_name" class="form-control">
                                         </div>
                                </div>
                            
                                <div class="hr-line-dashed"></div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" >Service Description</label>
                                    <div class="col-sm-10" name="service_desc" id="service_desc">
                                        <textarea  class="form-control" data-provide="markdown">
                                            
                                        </textarea>
                                    </div>
                                </div>
                                <div class="hr-line-dashed"></div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Service Icon</label>
                                         <div class="col-sm-10">
                                            <input type="text" name="service_icon" id="service_icon" class="form-control">
                                         </div>
                                </div>
                                <div class="hr-line-dashed"></div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Service Image</label>
                                         <div class="col-sm-10">
                                            <input type="text" name="service_image" id="service_image" class="form-control">
                                         </div>
                                </div>
                                <div class="hr-line-dashed"></div>
                                <div class="form-group">
                                    <div class="col-sm-4 col-sm-offset-2">
                                        <button class="btn btn-white" type="submit">Cancel</button>
                                        <button class="btn btn-primary" type="submit">Save changes</button>
                                    </div>
                                </div>
                                
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
          <?php include dirname(__FILE__) .'/include/footer.php'; ?> 