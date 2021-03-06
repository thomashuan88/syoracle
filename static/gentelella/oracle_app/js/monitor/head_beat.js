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

    $('.collapse-link').click(function () {
        var x_panel = $(this).closest('div.x_panel');
        var button = $(this).find('i');
        var content = x_panel.find('div.x_content');
        content.slideToggle(200);
        (x_panel.hasClass('fixed_height_390') ? x_panel.toggleClass('').toggleClass('fixed_height_390') : '');
        (x_panel.hasClass('fixed_height_320') ? x_panel.toggleClass('').toggleClass('fixed_height_320') : '');
        button.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
        setTimeout(function () {
            x_panel.resize();
        }, 50);
    });
    $('.collapse-link').click();
};

