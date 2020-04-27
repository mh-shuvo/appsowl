@extends('Layouts.SuperAdminDashboard') @section('style') .old_message{min-height:100px;} .ibox-title{background:;} .panel-title{font-size:16px} .ibox-content{border:1px solid #f2f9ff;} .bg_none{background:none;} @endsection @section('content')
<div class="row">
    <div class="col-sm-6">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h2>Write Support Subject in here..........</h2>
            </div>
            <div class="ibox-content">
                <div class="mail-box-header">
                    <div class="mail-tools tooltip-demo m-t-md">

                        <h3>
                           <span class="font-normal">Subject: </span>System Error in Point of Sale
                        </h3>
                        <h5>
                            <span class="pull-right font-normal">10:15AM 02 FEB 2014</span>
                            <span class="font-normal">From: </span>maadepartmentalstor@gmail.com
                         </h5>
                    </div>
                </div>
                <div class="mail-box">
                    <div class="mail-body">
                        <p>
                            Hello Jonathan!
                            <br/> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type <strong>specimen book.</strong>It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.
                        </p>
                    </div>
                    <div class="mail-attachment">
                        <p>
                            <span><i class="fa fa-paperclip"></i> 2 attachments - </span>
                            <a href="#">Download all</a> |
                            <a href="#">View all images</a>
                        </p>

                        <div class="attachment">
                            <div class="file-box">
                                <div class="file">
                                    <a href="#">
                                        <span class="corner"></span>

                                        <div class="icon">
                                            <i class="fa fa-file"></i>
                                        </div>
                                        <div class="file-name">
                                            Document_2014.doc
                                            <br/>
                                            <small>Added: Jan 11, 2014</small>
                                        </div>
                                    </a>
                                </div>

                            </div>
                            <div class="file-box">
                                <div class="file">
                                    <a href="#">
                                        <span class="corner"></span>

                                        <div class="image">
                                            <img alt="image" class="img-responsive" src="{{asset('public/img/p1.jpg')}}">
                                        </div>
                                        <div class="file-name">
                                            Italy street.jpg
                                            <br/>
                                            <small>Added: Jan 6, 2014</small>
                                        </div>
                                    </a>

                                </div>
                            </div>
                            <div class="file-box">
                                <div class="file">
                                    <a href="#">
                                        <span class="corner"></span>

                                        <div class="image">
                                            <img alt="image" class="img-responsive" src="{{asset('public/img/p2.jpg')}}">
                                        </div>
                                        <div class="file-name">
                                            My feel.png
                                            <br/>
                                            <small>Added: Jan 7, 2014</small>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <div class="mail-body text-right tooltip-demo">
                        <a class="btn btn-sm btn-white" onclick="showReplayForm()"><i class="fa fa-reply"></i> Reply</a>
                        <a class="btn btn-sm btn-white" href="mail_compose.html"><i class="fa fa-arrow-right"></i> Forward</a>
                        <button title="" data-placement="top" data-toggle="tooltip" type="button" data-original-title="Print" class="btn btn-sm btn-white"><i class="fa fa-print"></i> Print</button>
                        <button title="" data-placement="top" data-toggle="tooltip" data-original-title="Trash" class="btn btn-sm btn-white"><i class="fa fa-trash-o"></i> Remove</button>
                    </div>

                </div>
                {{--
                <form enctype="multipart/form-data">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <label>Name</label>
                                <input type="text" class="form-control" name="company_name" value="Software Galaxy Limited" readonly="">
                            </div>
                            <div class="col-sm-6">
                                <label>Email</label>
                                <input type="email" class="form-control" name="email" value="softwaregalaxyltd@gmail.com" readonly="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Message</label>
                        <textarea class="form-control" rows="3" readonly="">Hello Jonathan! Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.It was popularised in the 1960s with the release of
                        </textarea>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-3">
                                <img src="{{asset('public/img/a2.jpg')}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-9">
                                <label>Atachments</label>
                                <span id="attatch">
                                        <input type="file" name="atachment1" style="border:1px solid #e5e6e7;" class="form-control">
                                    </span>
                                <small>Allowed File Extensions: .jpg, .gif, .jpeg, .png</small>
                            </div>
                            <div class="col-sm-3">
                                <br>
                                <button class="pull-right btn btn-default btn-block" type="button" onclick="addFile()"><i class="fa fa-plus"></i> Add More</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-5 col-sm-offset-4">
                                <button class="btn btn-success">Submit</button>
                                <button class="btn btn-default">Cancel</button>
                            </div>
                        </div>
                    </div>
                </form> --}}
            </div>
        </div>
    </div>
    <div class="col-sm-6" id="replay_form">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h3>Support Reply From Super Admin</h3>
            </div>
            <div class="ibox-content">
                <div class="clearfix">
                    <form enctype="multipart/form-data">

                        <div class="form-group">
                            <label>Message</label>
                            <textarea class="form-control" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-9">
                                    <label>Atachments</label>
                                    <span id="attatch">
                                        <input type="file" name="atachment" style="border:1px solid #e5e6e7;" class="form-control">
                                     </span>
                                    <small>Allowed File Extensions: .jpg, .gif, .jpeg, .png</small>
                                </div>
                                <div class="col-sm-3">
                                    <br>
                                    <button class="pull-right btn btn-default btn-block" type="button" onclick="addFile()"><i class="fa fa-plus"></i> Add More</button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-5">
                                    <button class="btn btn-success" type="submit">Submit</button>
                                    <button class="btn btn-default" type="button" onclick="hideReplayForm()">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection {{-- @extends('Layouts.SuperAdminDashboard') @section('style') .old_message{min-height:70px;} .ibox-title{background:;} .panel-title{font-size:16px} .ibox-content{border:1px solid #f2f9ff;} .bg_none{background:none;} @endsection @section('content')
<div class="col-sm-12">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h2>View Ticket</h2>
        </div>
        <div class="ibox-content">
            <div>
                <div class="chat-activity-list">

                    <div class="chat-element">
                        <a href="#" class="pull-left">
                            <img alt="image" class="img-circle" src="{{asset('public/img/a2.jpg')}}">
                        </a>
                        <div class="media-body ">
                            <small class="pull-right text-navy">1m ago</small>
                            <strong>Mike Smith</strong>
                            <p class="m-b-xs">
                                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been
                            </p>
                            <small class="text-muted">Today 4:21 pm - 12.06.2014</small>
                        </div>
                    </div>

                    <div class="chat-element right">
                        <a href="#" class="pull-right">
                            <img alt="image" class="img-circle" src="{{asset('public/img/a4.jpg')}}">
                        </a>
                        <div class="media-body text-right ">
                            <small class="pull-left">5m ago</small>
                            <strong>John Smith</strong>
                            <p class="m-b-xs">
                                Lorem Ipsum is simply dummy text of the printing.
                            </p>
                            <small class="text-muted">Today 4:21 pm - 12.06.2014</small>
                        </div>
                    </div>

                    <div class="chat-element ">
                        <a href="#" class="pull-left">
                            <img alt="image" class="img-circle" src="{{asset('public/img/a2.jpg')}}">
                        </a>
                        <div class="media-body ">
                            <small class="pull-right">2h ago</small>
                            <strong>Mike Smith</strong>
                            <p class="m-b-xs">
                                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been
                            </p>
                            <small class="text-muted">Today 4:21 pm - 12.06.2014</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="chat-form">
                <form role="form">
                    <div class="form-group">
                        <textarea class="form-control" placeholder="Message"></textarea>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-sm btn-primary m-t-n-xs"><strong>Send message</strong></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endsection --}}