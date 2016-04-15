
    // $.jgrid.defaults.width = "100%";
    oracle_app.company.view.scripts = function() {
        // console.log($.jgrid);
        $("#jqGrid").jqGrid({
            url: oracle_app.baseurl + 'api/company/list',
            mtype: "GET",
            styleUI : 'Bootstrap',
            datatype: "json",
            colModel: oracle_app.company.view.colmodel,
            autowidth: true,
            viewrecords: true,
            rowNum:15,
            rowList:[15,25,35],
            sortname: 'createtime', 
            height: 500,
            width: '100%',
            pager: "#jqGridPager"
        });

      
    };




    