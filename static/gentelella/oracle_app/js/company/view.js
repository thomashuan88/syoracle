
    // $.jgrid.defaults.width = "100%";
    oracle_app.company.view.scripts = function() {
        // console.log($.jgrid);
        $("#jqGrid").jqGrid({
            url: oracle_app.baseurl + 'testing/output_json',
            mtype: "GET",
            styleUI : 'Bootstrap',
            datatype: "json",
            colModel: oracle_app.colmodel,
            autowidth: true,
            viewrecords: true,
            height: 250,
            width: '100%',
            rowNum: 20,
            pager: "#jqGridPager"
        });

      
    };




    