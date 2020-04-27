<?php defined('_AZ') or die('Restricted access'); 
include dirname(__FILE__) .'/include/header.php';
include dirname(__FILE__) .'/include/side_bar.php';
include dirname(__FILE__) .'/include/navbar.php';
?>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-4">
    
    <h2><?php echo trans('slider'); ?></h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{ url('/') }}"><?php echo trans('dashboard'); ?></a>
            </li>
            <li class="active">
                <strong><?php echo trans('slider'); ?></strong> 
            </li>
        </ol>
    </div>
    <div class="col-lg-8">
        <div class="title-action">
            <a href="admin/add-slide" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> <?php echo trans('add_slide'); ?></a>
        </div>
    
    </div>
</div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="post_status" ></div>
        
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">

                        <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover dataTables-example" >
                    <thead>
                    <tr>
                        <th class="text-center"><?php echo trans('id'); ?></th>
                        <th class="text-center"><?php echo trans('title'); ?></th>
                        <th class="text-center"><?php echo trans('slide_image'); ?></th>
                        <th class="text-center"><?php echo trans('action'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>1</td>
                        <td>portfolio images</td>
                        
                        <td class="text-center">
                            <div>
                            <a href="">
                                <img alt="image" src="images/services/1.jpg" width="80px">
                            </a>
                           </div></td>
                        <td class="text-center">
                            <a href="#supplier-form" data-toggle="modal" class="btn btn-primary btn-xs supplier_edit"><?php echo trans('edit'); ?></a>
                            <a href="" id="" class="btn btn-danger btn-xs m-b supplier_delete"><?php echo trans('delete'); ?></a>
                        </td>
                    </tr>
                 
                    </tbody>
                    <tfoot>
                    <tr>
                        <th class="text-center"><?php echo trans('id'); ?></th>
                        <th class="text-center"><?php echo trans('title'); ?></th>
                        <th class="text-center"><?php echo trans('slide_image'); ?></th>
                        <th class="text-center"><?php echo trans('action'); ?></th>
                    </tr>
                    </tfoot>
                    </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
        <?php include dirname(__FILE__) .'/include/footer.php'; ?>