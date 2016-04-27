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
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="companyname">Company Name <span class="required">*</span>
                        </label>
                        <div class="col-md-5 col-sm-6 col-xs-12">
                            <input type="text" id="companyname" name="companyname" required="required" data-parsley-remote data-parsley-type="alphanum" data-parsley-remote-validator="unique_companyname" data-parsley-remote-message="" data-parsley-trigger="change" class="form-control col-md-7 col-xs-12" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">Description 
                        </label>

                        <div class="col-md-5 col-sm-6 col-xs-12">
                            <textarea class="form-control" name="description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="prefix" class="control-label col-md-3 col-sm-3 col-xs-12">Prefix <span class="required">*</span></label>
                        <div class="col-md-2 col-sm-6 col-xs-12">
                            <input id="prefix" class="form-control col-md-1 col-xs-12" data-parsley-trigger="focusout" required="required" type="text" name="prefix">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="joburl">Job URL <span class="required">*</span>
                        </label>
                        <div class="col-md-5 col-sm-6 col-xs-12">
                            <input type="text" id="joburl" data-parsley-type="url" data-parsley-trigger="focusout" name="joburl" required="required" class="form-control col-md-7 col-xs-12">
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
