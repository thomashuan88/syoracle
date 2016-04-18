
oracle_app.company.view.scripts = function() {

    var thiscontent = $('#oracle_app_company_view_html');
    var source = {
        datatype: "json",
        datafields: [{
            name: 'id',
            type: 'number'
        }, {
            name: 'companyname',
            type: 'string'
        }, {
            name: 'description',
            type: 'string'
        }, {
            name: 'prefix',
            type: 'string'
        }, {
            name: 'joburl',
            type: 'string'
        }, {
            name: 'createtime',
            type: 'string'
        }, {
            name: 'createby',
            type: 'string'
        }, {
            name: 'updatetime',
            type: 'string'
        }, {
            name: 'updateby',
            type: 'string'
        }, {
            name: 'status',
            type: 'string'
        }],
        url: oracle_app.baseurl + 'api/company/list',
        root: 'Rows',
        beforeprocessing: function(data) {
            source.totalrecords = data[0].TotalRows;
        },
        sort: function() {
            // update the grid and send a request to the server.
            $("#jqxgrid").jqxGrid('updatebounddata', 'sort');
        },
        sortcolumn: 'createtime',
        sortdirection: 'asc'

    };
    var dataadapter = new $.jqx.dataAdapter(source);

    thiscontent.find("#jqxgrid").jqxGrid({
        source: dataadapter,
        theme : 'classic',
        width: '100%',
        // autoheight: true,
        height: '100%',
        pageable: true,
        sortable: true,
        virtualmode: true,
        autoshowloadelement:false,
        rendergridrows: function() {
            return dataadapter.records;
        },
        columns: oracle_app.company.view.colmodel
    });

    thiscontent.find('#oracle_app_company_view_add_btn').click(function(){
        $(".side-menu a[xhref='company/add']").trigger('click');
        return false;
    });
  
};




    