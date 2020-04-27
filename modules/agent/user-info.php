<?php defined('_AZ') or die('Restricted access'); 
include dirname(__FILE__) .'/include/header.php';
include dirname(__FILE__) .'/include/side_bar.php';
include dirname(__FILE__) .'/include/navbar.php';
?>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox">
                <div class="ibox-content">
				<span class="pull-right"><a href="agent/new-user" class="btn btn-primary"><?php echo trans('new_user'); ?></a></span>
                    <h2><?php echo trans('user_info'); ?></h2>
                    
                    <div class="table-responsive">
                        <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                            
                            <table class="table table-striped table-bordered table-hover dataTables-example dataTable text-center" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" role="grid">
                                <thead>
                                    <tr role="row">
                                        <th class="text-center"><?php echo trans('user_id'); ?></th>
                                        <th class="text-center"><?php echo trans('name'); ?></th>
                                        <th class="text-center"><?php echo trans('phone'); ?></th>
                                        <th class="text-center"><?php echo trans('registration_date'); ?></th>
                                        <th class="text-center"><?php echo trans('status'); ?></th>
                                        <th class="text-center"><?php echo trans('subscribed_software'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php $getallagentuserdetails = app('admin')->getallagentuserdetails($this->currentUser->id);
                                foreach($getallagentuserdetails as $value){
                                ?>

                                    <tr class="gradeA odd" role="row">
                                        <td class="text-center"><?php echo $value['user_id']; ?></td>
                                        <td><?php echo $value['first_name']." ".$value['last_name']; ?></td>
                                        <td><?php echo $value['phone']; ?></td>
                                        <td class="text-center"><?php echo getdatetime($value['register_date'],3); ?></td>
                                        <td class="text-center">
                                            <?php if ($value['banned']=="N") { ?>
                                               <span class="label label-primary"><?php echo trans('active');?></span></td>
                                            <?php } elseif($value['banned']=="Y") { ?>
                                                <span class="label label-danger"><?php echo trans('inactive');?></span></td>
                                            <?php } ?>
                                        <td class="text-center footable-visible footable-last-column">
											 <a id="<?php echo $value['user_id']; ?>" class='btn btn-primary btn-xs view user_subs_soft' ><?php echo trans('view'); ?></a>
										</td>
                                    </tr>
                                    <?PHP }?>
                                </tbody>
                                
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
</div>
</div>
<div class="modal inmodal in" id="user_soft_view" tabindex="-1" role="dialog" aria-hidden="true"  wfd-id="78">
    <div class="modal-dialog" wfd-id="79">
        <div class="modal-content animated bounceInRight" wfd-id="80">
            <div class="modal-header" wfd-id="85">
                <h5 class="modal-title">Subscribed Software</h5>
            </div>
            <div class="modal-body" wfd-id="82">
                <table class='table table-striped table-bordered text-center' id="usersublist">

                </table>
            </div>
            <div class="modal-footer" wfd-id="81">
                <button type="button" class="btn btn-white" data-dismiss="modal" wfd-id="238"><?php echo trans('close'); ?></button>
            </div>
        </div>
    </div>
</div>
[footer]
<script>
	$(document).ready(function () {
    $('.view').click(function () {
        $('#name').html($(this).data('user_name'));
        $('#user_name').html($(this).data('user_name'));
        $('#user_view_modal').modal('show');
    });
})

</script>
[/footer]
<?php include dirname(__FILE__) .'/include/footer.php'; ?>