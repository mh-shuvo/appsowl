<?php defined('_AZ') or die('Restricted access'); 
include dirname(__FILE__) .'/include/header.php';
include dirname(__FILE__) .'/include/side_bar.php';
include dirname(__FILE__) .'/include/navbar.php';

 //    if($currentUser->pos_report=='off'){
	   //  die('Restricted access');
    // }
    // else{
?>
[header]
<?php 
getCss('assets/system/css/plugins/dataTables/datatables.min.css',false,false);
getCss('assets/system/css/plugins/iCheck/custom.css');
?>
[/header]
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-4">
     
        <h2><?php echo trans('return_report'); ?></h2>
        <ol class="breadcrumb">
            <li>
                <a href=""><?php echo trans('dashboard'); ?></a>
            </li>
            <li class="active">
                <strong><?php echo trans('return_report'); ?></strong>
            </li>
        </ol>
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="post_status" ></div>
	<?php if($currentUser->pos_report=='edit'){?>
    <div class="ibox-content  m-b-sm border-bottom" id="sale_report_form">
        <form>
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group" id="data_1">
                        <label class="control-label" for="date_added"><?php echo trans('date_from'); ?></label>
                        <div class="input-group date">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input id="from_date" type="text" class="form-control" name="from_date" value="<?php if(isset($this->route['id'])) echo $this->route['id']; ?>">
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group" id="data_1">
                        <label class="control-label" for="date_from"><?php echo trans('date_to'); ?></label>
                        <div class="input-group date">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input id="to_date" type="text" class="form-control" name="to_date" value="<?php if(isset($this->route['id2'])) echo $this->route['id2']; ?>">
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="control-label" for="amount"><?php echo trans('action'); ?></label>
                        <button class="btn btn-primary block"><i class="fa fa-search"></i> <?php echo trans('search'); ?></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
	<?php } ?>
    
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
                        <table class="table table-striped table-bordered table-hover dataTables-example" data-title="Return Report">
                            <thead>
                                <tr>
                                    <th class="text-center"><?php echo trans('return_id'); ?></th>
                                    <th class="text-center"><?php echo trans('voucher_no'); ?></th>
                                    <th class="text-center"><?php echo trans('return_product_amount'); ?></th>
                                    <th class="text-center"><?php echo trans('return_product_type'); ?></th>
                                    <th class="text-center"><?php echo trans('return_product_added_by'); ?></th>
                                    <th class="text-center"><?php echo trans('customer'); ?></th>
                                    <th class="text-center"><?php echo trans('return_product_date'); ?></th>
									<?php if($currentUser->pos_report=='edit'){?>
                                    <th class="text-center not-show"><?php echo trans('action'); ?></th>
									<?php }?> 
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $default_enddate = date("Y-m-d");
                                    $date = new DateTime();
                                    $date->modify('-7 days');
                                    $default_startdate = $date->format('Y-m-d');
                                    $date = new DateTime();
                                    $date->modify('+1 days');
                                    $default_enddate = $date->format('Y-m-d');
                                    if(isset($this->route['id'])){
                                    $date = new DateTime($this->route['id2']);
                                    $date->modify('+1 days');
                                    $return_data = app('admin')->getwheredatefilter('pos_return','created_at',$this->route['id'],$date->format('Y-m-d'));
                                    }else{
                                    $return_data = app('admin')->getwheredatefilter('pos_return','created_at',$default_startdate,$default_enddate);
                                    }
                                    $total_amount=0;
                                    foreach($return_data as $return_data){
                                        $getuserdetails = app('admin')->getuserdetails($return_data['user_id']);
                                    ?>
                                    <tr>
                                        <td class="text-center"><?php echo $return_data['return_id']; ?></td>
                                        <td class="text-center"><?php echo $return_data['sales_id']; ?></td>
                                        <td class="text-center"><?php echo $return_data['return_total']; ?></td>
                                        <td class="text-center"><?php if ($return_data['return_status']==1) {
                                            echo "Cash";
                                        } elseif ($return_data['return_status']==0) {
                                            echo "Replace";
                                        } ?></td>
                                        <td class="text-center"><?php echo $getuserdetails['first_name'].' '.$getuserdetails['last_name']; ?></td>
                                        <td class="text-center">
                                            <?php
                                                $customerdetails = app('admin')->getwhereid('pos_customer','customer_id',$return_data['customer_id']);
                                                if ($customerdetails) {
                                                    if ($customerdetails['customer_phone']!=null) {
                                                        echo $customerdetails['customer_phone'];
                                                    }
                                                    elseif ($customerdetails['customer_name']!=null) {
                                                        echo $customerdetails['customer_name'];
                                                    }
                                                }else{
                                                    echo "Walk-in-customer";
                                                }?>
                                        </td>
                                        <td class="text-center"><?php echo getdatetime($return_data['created_at'],2); ?></td>
										<?php if($currentUser->pos_report=='edit'){?>
                                        <td class="text-center"><a href="pos/return-view/<?php echo $return_data['return_id']; ?>" class="btn btn-primary btn-sm">View</a></td>
										<?php } ?>
                                    </tr>
                                       
									<?php $total_amount=$total_amount+$return_data['return_total']; }?>
                                    <tr style="background-color:#d2d6de !important; color: #000;">
                                    <th class="text-center"><h3><b><?php echo trans('total')?></b></h3></th>
                                    <th></th>
                                    <th class="text-center text-danger"><h3><b><?php echo $total_amount;?></b></h3></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    
                                </tr>
                            </tbody>
                          
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
[footer]

<?php echo getJs('assets/system/js/plugins/dataTables/datatables.min.js',false,false);?>
<script>
    $(document).ready(function(){
        $('#data_1 .input-group.date').datepicker({
                todayBtn: "linked",
                format: "yyyy-mm-dd",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true
            });
        $('.dataTables-example').DataTable({
            pageLength: 25,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
                {extend: 'excel', title: 'return_report'},
                {extend: 'pdf', title: 'return_report'},

                {extend: 'print',
                 customize: function (win){
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '10px');

                        $(win.document.body).find('table')
                                .addClass('compact')
                                .css('font-size', 'inherit');
                }
                }
            ]

        });

    });

    function GetReportDateTime() {
    var fromdate = $("#from_date").val();
    var todate = $("#to_date").val();
    location.replace("pos/return-report/"+fromdate+"/"+todate); 
}

</script>
[/footer]

<?php include dirname(__FILE__) .'/include/footer.php';  } ?>