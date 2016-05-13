<div class="row"  id="oracle_app_<?php echo $path; ?>_html">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?php echo $title; ?></h2>

                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <br />
                <form id="oracle_app_<?php echo $path; ?>_form" class="form-horizontal form-label-left">
                    <input type="hidden" name="username" />
                    <div class="form-group">
                        <label for="password" class="control-label col-md-3 col-sm-3 col-xs-12">Old Password <span class="required">*</span></label>
                        <div class="col-md-2 col-sm-6 col-xs-12">
                            <input id="oldpassword" name="oldpassword" class="form-control col-md-1 col-xs-12" data-parsley-remote data-parsley-remote-validator="password_check" data-parsley-remote-message="" data-parsley-trigger="focusout"  required="required" type="password">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="control-label col-md-3 col-sm-3 col-xs-12">New Password <span class="required">*</span></label>
                        <div class="col-md-2 col-sm-6 col-xs-12">
                            <input id="newpassword" name="newpassword" class="form-control col-md-1 col-xs-12" data-parsley-trigger="focusout"  required="required" type="password">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="control-label col-md-3 col-sm-3 col-xs-12">Retype New Password <span class="required">*</span></label>
                        <div class="col-md-2 col-sm-6 col-xs-12">
                            <input id="passwordcheck" name="passwordcheck" class="form-control col-md-1 col-xs-12" data-parsley-trigger="focusout" data-parsley-equalto="#newpassword"  required="required" type="password">
                        </div>
                    </div>

                    <div class="ln_solid"></div>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <button type="submit" class="btn btn-success">Submit</button>
                            <button type="reset" class="btn btn-primary">Reset</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
