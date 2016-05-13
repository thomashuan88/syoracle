oracle_app.monitor.heart_beat.scripts = function() {
    var thiscontent = $('#oracle_app_monitor_heart_beat_view');
    var refresh_btn = $('.oracle_app_monitor_heart_beat_refresh_btn');

    refresh_btn.click(function() {
        var cid = $(this).attr('cid');
        var title = $(this).closest('div.x_title');
        title.find('.loading_img img').show();
        $.ajax({
            type: "GET",
            url: oracle_app.baseurl + 'api/monitor/heart_beat_refresh/' + cid,
            dataType: 'json',
            global: false,
            success: function(data) {
                title.siblings('div.x_content').html(data.html);   
                title.find('.loading_img img').hide();             
            },
            error: function(data) {
                var message = $.parseJSON(data.responseText);
                title.siblings('div.x_content').html('<span style="color:red">'+message.error.message.replace(/\(.*\)/,'')+'</span>');   
                title.find('.loading_img img').hide();             
            }
        });

    });

    var searchform = thiscontent.find('form.list_search_form');

    searchform.submit(function() {

        var companyname = $('input[name=companyname]', searchform).val();
        if (companyname != "") {
            $.ajax({
                type: "GET",
                url: oracle_app.baseurl + 'monitor/heart_beat/' + companyname,
                dataType: 'html',
                success: function(data) {
                    // console.log(data);
                    $('#nav_content').html(data); 
                    oracle_app.monitor.heart_beat.scripts();           
                }
            });
        } else {
            $.ajax({
                type: "GET",
                url: oracle_app.baseurl + 'monitor/heart_beat',
                dataType: 'html',
                success: function(data) {
                    // console.log(data);
                    $('#nav_content').html(data);
                    oracle_app.monitor.heart_beat.scripts();  
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

};

