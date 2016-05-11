<div id="oracle_app_monitor_database_view">
    <div class="x_title">
        <h2>Database</h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <p class="text-muted font-13 m-b-30">
            <form class="list_search_form">

                    <div class="control-group group_box">
                        <label class="list_label_first" for="companyname">Company Name : </label>
                        <div class="controls list_inputbox">
                            <div class="input-prepend input-group">
                                <input type="text" style="width: 200px" name="companyname" class="form-control" value="<?php echo empty($monitor_database_search['search_companyname'])?'':$monitor_database_search['search_companyname']; ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="control-group group_box">
                        <label class="list_label_first" for="tablename">Table Name : </label>
                        <div class="controls list_inputbox">
                            <div class="input-prepend input-group">
                                <input type="text" style="width: 200px" name="tablename" class="form-control" value="<?php echo empty($monitor_database_search['search_tablename'])?'':$monitor_database_search['search_tablename']; ?>" />
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-success search_btn" type="submit">Search</button>
            </form>
        </p>
    </div>
    <div class="clearfix"></div>
    <br />
    <?php if (empty($tabs)):?>
    <div class="col-xs-12">
        <span style="color:red">No Record found.</span>
    </div>
    <?php else: ?>
    <div class="col-xs-3">
        <!-- required for floating -->
        <!-- Nav tabs -->
        <ul class="nav nav-tabs tabs-left">
            <?php echo $tabs; ?>
        </ul>
    </div>
    <div class="col-xs-9">
        <!-- Tab panes -->
        <div class="tab-content">
            <?php echo $contents; ?>
        </div>
    </div>
    <?php endif; ?>
    <div class="clearfix"></div>    
</div>
