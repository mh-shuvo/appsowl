<?php defined('_AZ') or die('Restricted access');
include dirname(__FILE__) .'/include/header.php';
include dirname(__FILE__) .'/include/side_bar.php';
include dirname(__FILE__) .'/include/navbar.php';
if(isset($this->route['id'])){
    $ticket = app('root')->select("SELECT * FROM `as_ticket` WHERE `ticket_no`=:id",array("id"=>$this->route['id']));
    $chats = app('root')->select("SELECT * FROM `as_ticket` WHERE `ticket_for`=:t_for",array("t_for"=>$this->route['id']));
    $ticket = $ticket[0];
}
?>
    [header]
<?php
getCss('assets/system/css/plugins/jasny/jasny-bootstrap.min.css')
?>
    <style>
        .ScrollStyle{
            height:500px;
            overflow-y: auto;
            scroll-behavior: auto;
        }
    </style>
<?php getCss('assets/system/css/plugins/dataTables/datatables.min.css',false,false);?>
    [/header]
    <div class="row border-bottom white-bg page-heading">
        <div class="col-lg-4">
            <h2><?php echo trans('ticket_view'); ?></h2>
        </div>
    </div>
    <div class="wrapper wrapper-content">
        <div class="row animated fadeInRight">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="text-center float-e-margins p-md">
                        <a href="#" class="btn btn-sm btn-primary" id="changeVersion">Change style</a>
                    </div>
                    <div id="ibox-content">

                        <div id="vertical-timeline" class="vertical-container light-timeline left-orientation ScrollStyle">

                            <div class="vertical-timeline-block">
                                <div class="vertical-timeline-icon navy-bg">
                                    <i class="fa fa-user"></i>
                                </div>

                                <div class="vertical-timeline-content">
                                    <h2><?php echo $ticket['ticket_title']; ?></h2>
                                    <p><?php echo $ticket['ticket_details']; ?>
                                    </p>
                                    <!--a href="#" class="btn btn-sm btn-primary"> More info</a-->
                                    <span class="vertical-date">
	                                <?php echo date("D",strtotime($ticket['created_at']));?> <br/>
	                                <small><?php echo getdatetime($ticket['created_at'],3); ?></small><br/>
	                                <small>user</small>
	                            </span>
                                </div>
                            </div>
                            <div id="chat_data">

                            </div>
                        </div>

                    </div>
                    <div class="container">
                        <div class="row MessageForm">
                            <form method="post">
                                <?php
                                if(isset($this->route['id'])){
                                ?>
                                    <input type="hidden" name="ticket_no" value="<?php echo $this->route['id']; ?>" id="ticket_no">
                                <?php }?>
                                <div class="col-sm-10 col-sm-offset-1">
                                    <div class="ibox float-e-margins">
                                        <div class="ibox-content">
                                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                                <div class="form-control" data-trigger="fileinput"><i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div>
                                                <span class="input-group-addon btn btn-default btn-file"><span class="fileinput-new">Select file</span><span class="fileinput-exists">Change</span><input type="file" name="chat_document"></span>
                                                <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                                            </div>
                                            <textarea class="form-control textarea" rows="5" name="chat_text"></textarea>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-10 col-sm-offset-1">
                                    <button class="btn btn-primary btn-block" type="submit">
                                        <?php echo trans('submit');?>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    [footer]
<?php getJs('assets/system/js/plugins/dataTables/datatables.min.js',false,false);
    getJs('assets/system/js/plugins/jasny/jasny-bootstrap.min.js',false,false)

?>
    <script>
        $(document).ready(function(){

            $('#changeVersion').click(function(event) {
                event.preventDefault()
                $('#vertical-timeline').toggleClass('center-orientation');
            });
            loadChatData();
        });
		setInterval(function(){ loadChatData(); }, 1000);
        function loadChatData(){
            var ticket_no = $('#ticket_no').val();

            AS.Http.posthtml({"action" : "GetSupportChatData","ticket_id":ticket_no}, "pos/modal/", function (data) {
				if(data!=null){
						$("#chat_data").html(data);
							var scrollpos = $('.ScrollStyle').scrollTop();
							var scrollheight = $('.ScrollStyle').prop('scrollHeight');
							$('.ScrollStyle').scrollTop(scrollheight);
						}
						else{
								var html = '<p class="text-center">No Chat Found In here</p>';
								$("#chat_data").html('<p class="text-center">No Chat Found In here</p>');
							}
				});
				
				

        }
        $('.MessageForm form').validate({
            rules: {
                chat_text: {
                    required: true
                },

            },
            submitHandler: function (form) {
                AS.Http.PostSubmit(form, {"action" : "AddTicketChat"}, "ajax/", function (response) {
                    if(response.status=='success'){
                        loadChatData();
                        $('.textarea').val('');
                        $('.textarea').focus();
                    }
                });
            }
        });

    </script>
    [/footer]
<?php include dirname(__FILE__) .'/include/footer.php'; ?>