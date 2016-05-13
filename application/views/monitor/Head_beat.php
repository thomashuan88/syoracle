<div id="oracle_app_monitor_head_beat_view" style="background-color:white;margin-top:12px;padding:10px;padding-bottom:20px; margin-bottom:30px">
    <div class="page-title">
        <div class="x_title">
            <h2>Heart Beat</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <p class="text-muted font-13 m-b-30">
                <form class="list_search_form">

                        <div class="control-group group_box">
                            <label class="list_label_first" for="companyname">Company Name : </label>
                            <div class="controls list_inputbox">
                                <div class="input-prepend input-group">
                                    <input type="text" style="width: 200px" name="companyname" class="form-control" value="" />
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-success search_btn" type="submit">Search</button>
                </form>
            </p>
        </div>
    </div>
    <div class="clearfix"></div>
    <br />
    <?php echo empty($head_beat_blocks)?'<span style="color:red">No Record found.</span>':$head_beat_blocks; ?>
    <div class="clearfix"></div>
    <br /></br />
</div>