<?php defined('_AZ') or die('Restricted access'); 
include dirname(__FILE__) .'/include/header.php';
include dirname(__FILE__) .'/include/side_bar.php';
include dirname(__FILE__) .'/include/navbar.php';
?>
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-4">
            
                <h2><?php echo trans('team'); ?></h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('/') }}"><?php echo trans('dashboard'); ?></a>
                    </li>
                    <li class="active">
                        <strong><?php echo trans('team'); ?></strong> <?php /*echo str_replace('_', ' ', ucfirst($segment1)); */?>
                    </li>
                </ol>
        </div>
        <div class="col-lg-8">
            <div class="title-action">
                <a href="admin/add-team" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> <?php echo trans('add_team_member'); ?></a>
            </div>

        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><?php echo trans('about_detail'); ?></h5>
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

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover dataTables-example">
                                <thead>
                                    <tr>
                                        <th><?php echo trans('id'); ?></th>
                                        <th><?php echo trans('team_member_name'); ?></th>
                                        <th><?php echo trans('team_member_image'); ?></th>
                                        <th><?php echo trans('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="gradeX">
                                        <td>1</td>
                                        <td>Rokon</td>
                                        <td>
                                            <div>
                                                <a href="">
                                                    <img alt="image" src="images/services/1.jpg" width="80px">
                                                </a>
                                            </div>
                                        </td>
                                        <td class="center">
                                            <a href="#edit-product-form" data-toggle="modal" class="btn btn-primary btn-xs product_edit "><?php echo trans('edit'); ?></a>
                                            <a href="" id="" class="btn btn-danger btn-xs m-b product_delete"><?php echo trans('delete'); ?></a>

                                        </td>

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