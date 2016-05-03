function getReady() {
    var deferredReady = $.Deferred();
    $(document).ready(function() {
        deferredReady.resolve();
    });
    return deferredReady.promise();
}

var oracle_app = {};

oracle_app.return_json_err = function(str) {
    var json;
    try {
        json = $.parseJSON(str);
    } catch (e) {
        json = false;
    }
    return json;
};


$(function() {
    oracle_app.nav_content = $('#nav_content');
    oracle_app.oracleModal_message = $('#oracleModal_message').remove();

    oracle_app.login_img = $('#oracle_loading').remove();

    var appinfo = $('#appinfo');
    oracle_app.baseurl = appinfo.attr("baseurl");
    oracle_app.include_path = appinfo.attr("include-path");
    oracle_app.userinfo = {
        username: appinfo.attr('username')
    }

    oracle_app.company = { 
        view:{ 
            cache_nav_content: {},
            loadscripts: [
                "oracle_app/js/company/view.js"
            ],
            loadcss: [
                "jqwidgets/styles/jqx.base.css",
                "jqwidgets/styles/jqx.classic.css"
            ],
            el_remove: [
                "#ascrail2000",
                "#ascrail2000-hr",
                "#listBoxgridpagerlistjqxgrid",
                "#menuWrappergridmenujqxgrid"
            ]

        },
        add: {
            cache_nav_content: {},
            loadscripts: [
                "oracle_app/js/company/add.js"
            ],
            loadcss: [
                "css/editor/external/google-code-prettify/prettify.css",
                "css/editor/index.css",
                "css/select/select2.min.css",
                "css/switchery/switchery.min.css"
            ],
            el_remove: [
                "#ascrail2000",
                "#ascrail2000-hr",
                "#listBoxgridpagerlistjqxgrid",
                "#menuWrappergridmenujqxgrid"
            ]
        },
        edit: {
            cache_nav_content: {},
            loadscripts: [
                "oracle_app/js/company/edit.js"
            ],
            loadcss: [
                "css/editor/external/google-code-prettify/prettify.css",
                "css/editor/index.css",
                "css/select/select2.min.css",
                "css/switchery/switchery.min.css"
            ],
            el_remove: [
                "#ascrail2000",
                "#ascrail2000-hr",
                "#listBoxgridpagerlistjqxgrid",
                "#menuWrappergridmenujqxgrid"
            ]
        }
    };

    oracle_app.user = {
        view:{ 
            cache_nav_content: {},
            loadscripts: [
                "oracle_app/js/user/view.js"
            ],
            loadcss: [
                "jqwidgets/styles/jqx.base.css",
                "jqwidgets/styles/jqx.classic.css"
            ],
            el_remove: [
                "#ascrail2000",
                "#ascrail2000-hr",
                "#listBoxgridpagerlistjqxgrid",
                "#menuWrappergridmenujqxgrid"
            ]

        }
    };
    oracle_app.monitor = {
        head_beat:{ 
            cache_nav_content: {},
            loadscripts: [
                "oracle_app/js/monitor/head_beat.js"
            ],
            loadcss: [
                "jqwidgets/styles/jqx.base.css",
                "jqwidgets/styles/jqx.classic.css"
            ],
            el_remove: [
                "#ascrail2000",
                "#ascrail2000-hr",
                "#listBoxgridpagerlistjqxgrid",
                "#menuWrappergridmenujqxgrid"
            ]

        }
    };

    $('.oracle_app_userinfo_username').html(oracle_app.userinfo.username);

    
    //     $(".side-menu a[xhref='" + window.location.hash.replace(/^#\//,'') + "']").trigger('click');      
    // }

    if (window.location.hash != "") {
        $(window).trigger('hashchange');
    }
    $(window).on('hashchange', function() {
        var href = window.location.hash.replace(/^#\//,'');
        if (!$("a.orcle_ajaxload[xhref='" + href + "']").parent('.current-page').length) {
            $(".side-menu a[xhref='" + href + "']").trigger('click');
        }
    });

    oracle_app.showloading = function() {
        oracle_app.coverlayer = $('<div id="coverlayer"></div>');
        oracle_app.coverlayer.css({
            'position': 'fixed',
            'top': '0',
            'left': '0',
            'background': 'rgba(255,255,255,0.6)',
            'z-index': '1000',
            'width': '100%',
            'height': '100%'
        });
        oracle_app.coverlayer.append(oracle_app.login_img);
        oracle_app.coverlayer.appendTo('body');
    };

    oracle_app.removeloading = function() {
        oracle_app.coverlayer.remove();
    };

    $(document).ajaxStart(function() {
        oracle_app.showloading();
    });
    $(document).ajaxStop(function() {
        oracle_app.removeloading();
    });
    $(document).ajaxSuccess(function(evt, jqXHR, settings) {
        var res = oracle_app.return_json_err(jqXHR.responseText);
        if (res !== false) {
            if (res.status == 'error' && res.type == 'session_expire') {
                // show model then jump to login
                swal({
                  title: "Warning!",
                  text: "You Session is expired, please login again.",
                  type: "warning",
                  showCancelButton: false,
                  closeOnConfirm: false
                },
                function(){
                    window.location.hash = "";
                    window.location = oracle_app.baseurl + 'login';
                });
            }
        }
    });

    oracle_app.load_module_content = function(href) {

        var controller = href.split("/");

        for (var x in oracle_app[controller[0]][controller[1]].el_remove) {
            $(oracle_app[controller[0]][controller[1]].el_remove[x]).remove();
        }

        // if (oracle_app[controller[0]][controller[1]].cache_nav_content.length) {
        //     $('#nav_content').html('');
        //     oracle_app[controller[0]][controller[1]].cache_nav_content.appendTo('#nav_content');
        //     oracle_app[controller[0]][controller[1]].scripts();
        //     return false;
        // }

        var loadcss = oracle_app[controller[0]][controller[1]].loadcss;
        for (var x in loadcss) {
            $("<link/>", {
               rel: "stylesheet",
               type: "text/css",
               href: oracle_app.include_path + loadcss[x]
            }).appendTo("head");
        }

        var ajax_load_arr = [];

        // ajax_load_arr.push(getReady());
        ajax_load_arr = $.load_ajax_arr(ajax_load_arr, oracle_app[controller[0]][controller[1]].loadscripts, oracle_app.include_path, 'script', 'nocache');

        oracle_app.load_content = $.ajax({ url:oracle_app.baseurl + href , dataType: 'html' });
        ajax_load_arr.push(oracle_app.load_content);
        // console.log(ajax_load_arr);
        
        var interval_id = setInterval(function() {
            var allitem = 0;
            var okitem = 0;                 
            for (var x in ajax_load_arr) {
                if (ajax_load_arr[x].statusText == 'OK') {
                    okitem++;
                }
                allitem++;
            }
            if (allitem === okitem) {
                clearInterval(interval_id);

                var res = oracle_app.return_json_err(oracle_app.load_content.responseText);

                if (res === false) {

                    $('#nav_content').html(oracle_app.load_content.responseText);
                    window.location = oracle_app.baseurl + '#/' + href;
                    oracle_app[controller[0]][controller[1]].scripts();
                    
                    oracle_app[controller[0]][controller[1]].cache_nav_content = $('#oracle_app_'+controller[0]+'_'+controller[1]+'_html');
                } else {
                    if (res.status == 'error') { 
                        // show model then jump to login
                        swal({
                          title: "Warning!",
                          text: "You Session is expired, please login again.",
                          type: "warning",
                          showCancelButton: false,
                          closeOnConfirm: false
                        },
                        function(){
                            window.location.hash = "";
                            window.location = oracle_app.baseurl + 'login';
                        });
                    }
                }
            }
        }, 100);
        // $.when.apply($, ajax_load_arr).done(function(x) {

        //     oracle_app.load_content.success(function(){
        //         var res = oracle_app.return_json_err(oracle_app.load_content.responseText);

        //         if (res === false) {

        //             $('#nav_content').html(oracle_app.load_content.responseText);
                    
        //             oracle_app[controller[0]][controller[1]].scripts();
        //             oracle_app[controller[0]][controller[1]].cache_nav_content = $('#oracle_app_'+controller[0]+'_'+controller[1]+'_html');
        //         } else {
        //             if (res.status == 'error') { 
        //                 // show model then jump to login
        //                 oracle_app.oracleModal_message.modal('show');
        //             }
        //         }
        //     });
        // });
    }

    oracle_app.nav_row_edit_link = function(obj) {
        var controller = obj.path.split("/");
        oracle_app[controller[0]][controller[1]].row_data = obj.row_data;
        oracle_app.load_module_content(obj.path);
    };

    oracle_app.nav_link = function(obj) { 
        var href = $(obj).attr('xhref');
        $(obj).parent().siblings().removeClass('current-page');
        $(obj).parent().addClass('current-page');

        oracle_app.load_module_content(href);

        return false;
    };

    $("a.orcle_ajaxload").click(function(){
        oracle_app.nav_link(this);
        return false;
    });
    
});

$.getMultiScripts = function(arr, path) {
    var _arr = $.map(arr, function(scr) {
        return $.ajax({ url: (path||"") + scr, dataType: "script" });
    });

    _arr.push(getReady());

    return $.when.apply($, _arr);
};

$.load_ajax_arr = function(arr_first, arr_second, path, type, cache) {
    var _arr = []
    for(var x in arr_second) {
        _arr.push($.ajax({ 
            url: (path||"") + arr_second[x], 
            dataType: type, 
            cache:((cache=='cache')?true:false)
        }));
        // console.log(_arr[x]);
    }
    return $.merge(arr_first, _arr);
};
