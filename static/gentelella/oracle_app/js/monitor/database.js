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
                // console.log(data);
                if (data.status == 'success') {
                    $(href, thiscontent).html(data.html);            
                }
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
    });

    var searchform = thiscontent.find('form.list_search_form');

    searchform.submit(function() {

        var url = oracle_app.baseurl + 'monitor/database';
        var companyname = $('input[name=companyname]', searchform).val();
        var tablename = $('input[name=tablename]', searchform).val();
        var param = "";

        if (companyname != "" || tablename != "") {
            param = "?companyname="+companyname+"&tablename="+tablename;
        }
        $.ajax({
            type: "GET",
            url: url + param,
            dataType: 'html',
            success: function(data) {
                // console.log(data);
                $('#nav_content').html(data);
                oracle_app.monitor.database.scripts();  
            }
        }); 
        return false;
    });

    oracle_app.check_database_expire = setInterval(function(){
        // check if database expire
        var url = oracle_app.baseurl + 'api/monitor/database_interval_check';
        $.ajax({
            type: "GET",
            url: url,
            dataType: 'json',
            success: function(data) {
                console.log(data);
                if (data.status == 'error') {
                    clearInterval(oracle_app.check_database_expire);
                    delete oracle_app.check_database_expire;
                    oracle_app.load_module_content('monitor/database');
                }
            }
        });
    }, 123*1000);
};

