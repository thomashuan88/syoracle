<div id="oracle_app_monitor_redis_view" style="background-color:white;margin-top:12px;padding:10px;padding-bottom:20px; margin-bottom:30px">
    <div class="x_title">
        <h2>Redis Cache</h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <p class="text-muted font-13 m-b-30">
            <form class="list_search_form">

                    <div class="control-group group_box">
                        <label class="list_label_first" for="companyname">Company Name : </label>
                        <div class="controls list_inputbox">
                            <div class="input-prepend input-group">
                                <input type="text" style="width: 200px" name="companyname" class="form-control" value="<?php echo empty($monitor_redis_search['search_companyname'])?'':$monitor_redis_search['search_companyname']; ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="control-group group_box">
                        <label class="list_label_first" for="rediskey">Key : </label>
                        <div class="controls list_inputbox">
                            <div class="input-prepend input-group">
                                <input type="text" style="width: 200px" name="rediskey" class="form-control" value="<?php echo empty($monitor_redis_search['search_rediskey'])?'':$monitor_redis_search['search_rediskey']; ?>" />
                            </div>
                        </div>
                    </div>

                    <button class="btn btn-success search_btn" type="submit">Search</button>
            </form>
        </p>
    </div>
    <div class="clearfix"></div>
    <br />
    <div style="background-color:#eee;padding-top:10px;padding-right:10px;padding-bottom:10px;">
        <?php if (empty($tabs)):?>
        <div class="col-xs-12">
            <span style="color:red">No Record found.</span>
        </div>
        <?php else: ?>
        <div class="col-xs-3" style="padding-right:0">
            <!-- required for floating -->
            <!-- Nav tabs -->
            <ul class="nav nav-tabs tabs-left">
                <?php echo $tabs; ?>
            </ul>
        </div>
        <div class="col-xs-9" style="padding-right:0px;padding-left:0;background-color:white;margin-top:2px;">
            <!-- Tab panes -->
            <div class="tab-content" style="padding:10px;">
                <?php echo $contents; ?>
            </div>
        </div>
        <?php endif;?>
        <div class="clearfix"></div>    
    </div>
</div>
