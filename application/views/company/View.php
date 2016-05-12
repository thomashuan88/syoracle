<div class="row" id="oracle_app_company_view_html">
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Company List</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li class="current-page"><a href="#" id="oracle_app_company_view_add_btn"><i class="fa fa-plus-square"></i> Add</a>
                </li>
                <li class="current-page"><a href="#" xhref="company/edit" id="oracle_app_company_view_edit_btn"><i class="fa fa-edit"></i> Edit</a>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <p class="text-muted font-13 m-b-30">
                <form class="list_search_form">
                    <fieldset>

                        <div class="control-group group_box">
                            <label class="list_label_first" for="companyname">Company Name : </label>
                            <div class="controls list_inputbox">
                                <div class="input-prepend input-group">
                                    <input type="text" style="width: 200px" name="companyname" class="form-control" value="<?php echo empty($company_list_search['search_companyname'])?'':$company_list_search['search_companyname']; ?>" />
                                </div>
                            </div>
                        </div>

                        <div class="control-group group_box">
                            <label class="list_label" for="createtime">Create Time : </label>
                            <div class="controls list_inputbox">
                                <div class="input-prepend input-group">
                                    <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                                    <input type="text" style="width: 200px" name="createtime" class="form-control" value="<?php echo empty($company_list_search['search_createtime'])?'':$company_list_search['search_createtime']; ?>" />
                                </div>
                            </div>
                        </div>

                        <div class="control-group group_box">
                            <label class="list_label" for="prefix">Prefix : </label>
                            <div class="controls list_inputbox">
                                <div class="input-prepend input-group">
                                    <select name="prefix" class="form-control">
                                        <option value="">-- Select Prefix --</option>
                                        <?php echo $prefixlist; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="control-group group_box">
                            <label class="list_label" for="status">Status : </label>
                            <div class="controls list_inputbox">
                                <div class="input-prepend input-group">
                                    <select name="status" class="form-control">
                                        <option value="">-- Select Status --</option>
                                        <?php echo $statuslist; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-success search_btn" type="submit">Search</button>
                    </fieldset>
                </form>
            </p>
        </div>
        <div style="padding-top:40px;padding-bottom: 6px;" id="jqGrid_container">
            <!--             
            <table id="jqGrid"></table>
            <div id="jqGridPager"></div>   -->    

            <div id="oracle_app_company_view_jqxgrid"></div>
  
        </div>
        <!-- table -->

    </div>
</div>
</div>