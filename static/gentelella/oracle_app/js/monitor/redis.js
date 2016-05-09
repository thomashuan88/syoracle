oracle_app.monitor.redis.scripts = function() {
    var thiscontent = $('#oracle_app_monitor_redis_view');

    thiscontent.find('a.companytab').click(function() {
        var cid = $(this).attr('cid');
        var href = $(this).attr('href');

        $.ajax({
            type: "GET",
            url: oracle_app.baseurl + 'api/monitor/redis/' + cid,
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

        var companyname = $('input[name=companyname]', searchform).val();
        if (companyname != "") {
            $.ajax({
                type: "GET",
                url: oracle_app.baseurl + 'monitor/redis/' + companyname,
                dataType: 'html',
                success: function(data) {
                    // console.log(data);
                    $('#nav_content').html(data); 
                    oracle_app.monitor.redis.scripts();           
                }
            });
        } else {
            $.ajax({
                type: "GET",
                url: oracle_app.baseurl + 'monitor/redis',
                dataType: 'html',
                success: function(data) {
                    // console.log(data);
                    $('#nav_content').html(data);
                    oracle_app.monitor.redis.scripts();  
                }
            });            
        }

        return false;
    });
};

