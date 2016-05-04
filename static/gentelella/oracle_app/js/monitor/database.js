oracle_app.monitor.database.scripts = function() {

    var thiscontent = $('#oracle_app_monitor_database_view');

    thiscontent.find('a.companytab').click(function() {
        var cid = $(this).attr('cid');
        var href = $(this).attr('href');

        $.ajax({
            type: "GET",
            url: oracle_app.baseurl + 'api/monitor/database_struct/' + cid,
            dataType: 'json',
            success: function(data) {
                 $(href, thiscontent).html(data);            
            },
            error: function(data) {
                var res = oracle_app.return_json_err(data.responseText);
                if (res.status == false || res.status == "error") {
                    $(href, thiscontent).html('<span style="color:red">'+((res.status=="error")?res.msg:res.error.message.replace(/\(.*\)/,''))+'</span>');
                } else {
                    $(href, thiscontent).html(data);
                }
                
               
            }
        });
    })
};

