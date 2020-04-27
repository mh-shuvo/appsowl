<?php defined('_AZ') or die('Restricted access');
include dirname(__FILE__) .'/include/header.php';
include dirname(__FILE__) .'/include/side_bar.php';
include dirname(__FILE__) .'/include/navbar.php';
?>
[header]
<?php getCss('assets/system/css/plugins/dataTables/datatables.min.css',false,false);?>
[/header]
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-4">
            <h2><?php echo trans('ticket_list'); ?></h2>
            <ol class="breadcrumb">
                <li>
                    <a href="/pos/home"><?php echo trans('dashboard'); ?></a>
                </li>
                <li class="active">
                    <strong><?php echo trans('ticket_list'); ?></strong>
                </li>
            </ol>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">

        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-content">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover ticket_table" data-title="Ticket List"></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    [footer]
<?php getJs('assets/system/js/plugins/dataTables/datatables.min.js',false,false);?>
<script>
    DataTable(false);

    function DataTable(FilterType) {
        AS.Http.GetDataTable('.ticket_table',TableDataColums(),{ action : "GetTicketData"},"pos/filter/",FilterType);
    }
    function TableDataColums(){
        return [
            { "title": $_lang.ticket_no,"class": "text-center", data : 'ticket_no' },
            { "title": $_lang.ticket_title,"class": "text-center", data : 'ticket_title' },
            { "title": $_lang.added_by,"class": "text-center", data : 'added_by' },
            { "title": $_lang.status,"class": "text-center",
                orderable: false,
                render : function (data, type, row) {
                    var html ='';
                    if(row.status == 'complete'){
                        html ='<label class="label label-primary">'+row.status+'</label>';
                    }
                    else{
                        html ='<label class="label label-danger">'+row.status+'</label>';
                    }
                    return html;
                }
            },
            { "title": $_lang.priority_level,"class": "text-center", data : 'priority' },
            { "title": $_lang.action,"class": "text-center not-show",
                orderable: false,
                render: function (data, type, row) {
                    var html = '<div class="btn-group">'
                        +'<button type="button" class="btn btn-info dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">'+$_lang.action+'<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>'
                        +'<ul class="dropdown-menu pull-right" role="menu">'
                        +'<li><a href="pos/ticket_view/'+row.ticket_no+'" class="purchase_view"><i class="fa fa-eye"></i> '+$_lang.view+'</a></li>'
                        +'</ul>';
                    return html;

                }
            }

        ];
    }
    $(document).on('click','.read_more_ticket',function(){
        var id = $(this).attr("t_no");
        $(".show_modal").remove();
        AS.Http.posthtml({"action" : "GetTicketReadMoreData","ticket_no":id}, "pos/modal/", function (data) {
            $(".modal_status").html(data);
            $(".show_modal").modal("show");
        });
    });

</script>
[/footer]
<?php include dirname(__FILE__) .'/include/footer.php'; ?>