oracle_app.user.view.scripts = function() {

    var thiscontent = $('#oracle_app_user_view_html');

    thiscontent.find('input[name=createtime]').daterangepicker({
        locale: {
          format: 'YYYY-MM-DD'
        },
        "opens": "left"
    }).on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });

    var source = {
        datatype: "json",
        datafields: [{
            name: 'id',
            type: 'number'
        }, {
            name: 'username',
            type: 'string'
        }, {
            name: 'email',
            type: 'string'
        }, {
            name: 'password',
            type: 'string'
        }, {
            name: 'usergroup',
            type: 'string'
        }, {
            name: 'usergroup_name',
            type: 'string'
        }, {
            name: 'last_logintime',
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
        url: oracle_app.baseurl + 'api/user/list',
        root: 'Rows',
        data:{},
        beforeprocessing: function(data) {
            source.totalrecords = data[0].TotalRows;
        },
        sort: function() {
            // update the grid and send a request to the server.
            $("#oracle_app_user_view_jqxgrid").jqxGrid('updatebounddata', 'sort');
        },
        sortcolumn: 'createtime',
        sortdirection: 'asc'

    };

    var cellsrenderer = function(row, column, value, defaultHtml, columnSettings, rowData) {
        if (rowData.status == 'inactive') {
            var element = $(defaultHtml);
            // console.log(element);
            element.css({
                'color': 'red'
            });
            return element[0].outerHTML;
        }
        return defaultHtml;
    };

    var colmodel = [
        { text: 'ID', datafield: 'id', width: '5%', cellsrenderer: cellsrenderer },
        { text: 'UserName', datafield: 'username', width: '15%', cellsrenderer: cellsrenderer },
        { text: 'Email', datafield: 'email', width: '20%', cellsrenderer: cellsrenderer },
        { text: 'Group', datafield: 'usergroup_name', width: '5%', cellsrenderer: cellsrenderer },
        { text: 'Last Login', datafield: 'last_logintime', width: '10%', cellsrenderer: cellsrenderer },
        { text: 'Created Time', datafield: 'createtime', width: '10%', cellsrenderer: cellsrenderer },
        { text: 'Created By', datafield: 'createby', width: '10%', cellsrenderer: cellsrenderer },
        { text: 'Update Time', datafield: 'updatetime', width: '10%', cellsrenderer: cellsrenderer },
        { text: 'Update By', datafield: 'updateby', width: '10%', cellsrenderer: cellsrenderer },
        { text: 'Status', datafield: 'status', width: '5%', cellsrenderer: cellsrenderer }
    ];

    var jqxgrid = $("#oracle_app_user_view_jqxgrid", thiscontent);
    var dataadapter = new $.jqx.dataAdapter(source);
    jqxgrid.jqxGrid({
        source: dataadapter,
        theme : 'customgrid',
        width: '100%',
        autoheight: true,
        //height: '100%',
        pagesize: 10,
        altrows: true,
        pageable: true,
        sortable: true,
        virtualmode: true,
        autoshowloadelement:false,
        pagermode: 'simple',
        rendergridrows: function() {
            return dataadapter.records;
        },
        columns: colmodel
    });

    jqxgrid.on('rowclick', function (event) {
        var index = jqxgrid.jqxGrid('getselectedrowindex');
        var clickedIndex = event.args.rowindex;
        if (clickedIndex == index) {
            setTimeout(function () {
                jqxgrid.jqxGrid('clearselection');
            }, 10);
        }
    });

    thiscontent.find('#oracle_app_user_view_add_btn').click(function(){
        // $(".side-menu a[xhref='user/add']").trigger('click');
        oracle_app.load_module_content('user/add');
        return false;
    });
    thiscontent.find('#oracle_app_user_view_edit_btn').click(function(){
        var getselectedrowindexes = jqxgrid.jqxGrid('getselectedrowindexes');
        if (getselectedrowindexes.length > 0)
        {
            var selectedRowData = jqxgrid.jqxGrid('getrowdata', getselectedrowindexes[0]);
            if (!selectedRowData) {
                swal("You haven't select a row", "", "error");
            } else {
                oracle_app.user.view.row_edit = selectedRowData;
                oracle_app.nav_row_edit_link({
                    path: "user/edit",
                    row_data: selectedRowData
                });
                return false;
            }
        } else {
            swal("You haven't select a row", "", "error");
        }
        return false;

    });

    var searchform = thiscontent.find('form.list_search_form');

    searchform.submit(function() {

        dataadapter = new $.jqx.dataAdapter(source, {
            formatData: function(data) {
                data.search_username = searchform.find('input[name=username]').val();
                data.search_createtime = searchform.find('input[name=createtime]').val();
                return data;
            }
        });
        jqxgrid.jqxGrid({
            source: dataadapter
        });
        return false;
    })

};
