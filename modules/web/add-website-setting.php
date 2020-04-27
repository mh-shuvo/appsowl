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
                        <h2>Website Settings</small></h2>
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
                        <form method="post" class="form-horizontal" id="dropzoneForm" enctype="multipart/form-data" ><!-- id="dropzoneForm" -->
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Title</label>
                                <div class="col-sm-10">
                                    <input type="text" name="title" id="title" class="form-control">
                                </div>
                            </div>

                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Short Description</label>
                                <div class="col-sm-10" name="desc" id="portfolio_desc">

                                    <textarea class="form-control" data-provide="markdown">

                                    </textarea>
                                </div>
                            </div>

                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Image</label>
                                <div class="col-sm-10">

                                    <input name="file" type="file" name="image" multiple />

                                </div>
                            </div>

                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Image Alt Tag</label>
                                <div class="col-sm-10">
                                    <input type="text" name="alt" id="alt" class="form-control">
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Contact</label>
                                <div class="col-sm-10" name="contact" id="contact">

                                    <textarea class="form-control" data-provide="markdown">

                                    </textarea>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Social Media
                                    <br>
                                    <small class="text-navy">Select your social media and url</small></label>
                                <div class="col-sm-10">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>
                                            <input type="checkbox" checked="" value="option1" id="optionsRadios1" name="optionsRadios">
                                                <select class="form-control m-b" name="social1">
                                                    <option>Facebook</option>
                                                    <option>Twitter</option>
                                                    <option>Linkedin</option>
                                                    <option>Google +</option>
                                                    <option>Instagram</option>
                                                    <option>Pinterest</option>
                                                </select>
                                            </label>
                                             <input type="text" placeholder="Your Social Url" class="form-control">
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>
                                                <input type="checkbox" value="option2" id="optionsRadios2" name="optionsRadios">
                                                <select class="form-control m-b" name="social2">
                                                    <option>Twitter</option>
                                                    <option>Facebook</option>
                                                    <option>Linkedin</option>
                                                    <option>Google +</option>
                                                    <option>Instagram</option>
                                                    <option>Pinterest</option>
                                                </select>
                                            </label>
                                            <input type="text" placeholder="Your Social Url" class="form-control">
                                        </div>
                                        <!-- <div class="col-md-10">
                                            
                                        </div> -->

                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>
                                                <input type="checkbox" value="option3" id="optionsRadios3" name="optionsRadios">
                                                <select class="form-control m-b" name="social3">
                                                    <option>Linkedin</option>
                                                    <option>Twitter</option>
                                                    <option>Facebook</option>
                                                    <option>Google +</option>
                                                    <option>Instagram</option>
                                                    <option>Pinterest</option>
                                                </select>
                                            </label>
                                            <input type="text" placeholder="Your Social Url" class="form-control">
                                        </div>
                                        <!-- <div class="col-md-10">
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                            

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