oracle_app.monitor.database.scripts = function() {

    var thiscontent = $('#oracle_app_monitor_database_view');

    thiscontent.find('a.companytab').click(function() {
        var cid = $(this).attr('cid');
        var href = $(this).attr('href');
        $.get(oracle_app.baseurl + 'api/monitor/database_struct/'+ cid, function(data) {
            $(href).html(data);
        }, 'html');
    })
};

