<div class="row"  id="oracle_app_<?php echo $path; ?>_html">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?php echo $title; ?></h2>
                    <!--                 
                    <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#">Settings 1</a>
                            </li>
                            <li><a href="#">Settings 2</a>
                            </li>
                        </ul>
                    </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                </ul> -->
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <br />
                <form id="oracle_app_<?php echo $path; ?>_form" class="form-horizontal form-label-left">
                    <input type="hidden" name="id" />
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="companyname">User Name <span class="required">*</span>
                        </label>
                        <div class="col-md-5 col-sm-6 col-xs-12">
                            <input type="text" id="username" name="username" required="required" data-parsley-remote data-parsley-type="alphanum" data-parsley-remote-validator="unique_username" data-parsley-remote-message="" data-parsley-trigger="change" class="form-control col-md-7 col-xs-12" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="control-label col-md-3 col-sm-3 col-xs-12">Password <span class="required">*</span></label>
                        <div class="col-md-2 col-sm-6 col-xs-12">
                            <input id="password" class="form-control col-md-1 col-xs-12" data-parsley-trigger="focusout" required="required" type="password" name="password">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email <span class="required">*</span>
                        </label>
                        <div class="col-md-5 col-sm-6 col-xs-12">
                            <input type="email" id="email" data-parsley-type="url" data-parsley-trigger="focusout" name="email" required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">User Group <span class="required">*</span></label>
                        <div class="col-md-2 col-sm-6 col-xs-12">
                            <select class="form-control" name="usergroup" required="required">
                                <option value="">-- Select Group --</option>
                                <option value="1">root</option>
                                <option value="2">Super User</option>
                                <option value="3">Normal User</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Status</label>
                        <div class="col-md-6 col-sm-6 col-xs-12" style="padding-top:6px; padding-left: 20px;">
                            <input type="radio" class="flat" name="status" id="status_active" value="active" checked="" /> Active 
                            <input type="radio" class="flat" name="status" id="status_inactive" value="inactive"  /> Inactive
                        </div>
                    </div>
                    <div class="ln_solid"></div>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <button type="reset" class="btn btn-primary">Cancel</button>
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
