oracle_app.monitor.head_beat.scripts = function() {
    var thiscontent = $('#oracle_app_monitor_head_beat_view');
    var refresh_btn = $('.oracle_app_monitor_head_beat_refresh_btn');

    refresh_btn.click(function() {
        var cid = $(this).attr('cid');
        var title = $(this).closest('div.x_title');
        var small = title.find('h2 small');

        $.ajax({
            type: "GET",
            url: oracle_app.baseurl + 'api/monitor/head_beat_refresh/' + cid,
            dataType: 'json',
            success: function(data) {
                small.html('<i class="fa fa-check-square" style="color:green"></i> Active');
                title.siblings('div.x_content').html(data.html);                
            },
            error: function(data) {
                console.log(data);
                var message = $.parseJSON(data.responseText);
                small.html('<i class="fa fa-minus-square" style="color:red"></i> Inactive');
                title.siblings('div.x_content').html('<span style="color:red">'+message.error.message.replace(/\(.*\)/,'')+'</span>');                
            }
        });

    });

    var searchform = thiscontent.find('form.list_search_form');

    searchform.submit(function() {

        var companyname = $('input[name=companyname]', searchform).val();
        if (companyname != "") {
            $.ajax({
                type: "GET",
                url: oracle_app.baseurl + 'monitor/head_beat/' + companyname,
                dataType: 'html',
                success: function(data) {
                    // console.log(data);
                    $('#nav_content').html(data); 
                    oracle_app.monitor.head_beat.scripts();           
                }
            });
        } else {
            $.ajax({
                type: "GET",
                url: oracle_app.baseurl + 'monitor/head_beat',
                dataType: 'html',
                success: function(data) {
                    // console.log(data);
                    $('#nav_content').html(data);
                    oracle_app.monitor.head_beat.scripts();  
                }
            });            
        }

        return false;
    });
};

