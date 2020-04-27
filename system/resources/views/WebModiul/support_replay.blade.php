@extends('Layouts.SuperAdminDashboard') 
@section('style') 
.old_message{min-height:100px;} .ibox-title{background:;} .panel-title{font-size:16px} .ibox-content{border:1px solid #f2f9ff;} .bg_none{background:none;} 
@endsection 
@section('content')
<div class="row">
<div class="col-sm-6">
    <div class="ibox">
        <div class="ibox-title">
            <h2>Write Support Subject in here..........</h2>
        </div>
        <div class="ibox-content">
             <form enctype="multipart/form-data">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Name</label>
                                    <input type="text" class="form-control" name="company_name" value ="Software Galaxy Limited" readonly="">
                                </div>
                                <div class="col-sm-6">
                                    <label>Email</label>
                                    <input type="email" class="form-control" name="email" value="softwaregalaxyltd@gmail.com" readonly="">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Message</label>
                            <textarea class="form-control" rows="3">

                            </textarea>
                        </div>
                        <div class="form-group">
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
              </form>
        </div>
     </div>
    </div>
</div>    
@endsection