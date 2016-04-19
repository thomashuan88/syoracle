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
    oracle_app.oracleModal_comfirm = $('#oracleModal_comfirm').remove();
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
            loadmodules:[

            ],
            loadscripts: [
                "oracle_app/js/company/view.js"
            ],
            loadcss: [
                "jqwidgets/styles/jqx.base.css",
                "jqwidgets/styles/jqx.classic.css"
            ],
            colmodel: [
                { text: 'Company Id', datafield: 'id', width: '5%' },
                { text: 'Company Name', datafield: 'companyname', width: '15%' },
                { text: 'Description', datafield: 'description', width: '20%' },
                { text: 'Prefix', datafield: 'prefix', width: '5%' },
                { text: 'Job URL', datafield: 'joburl', width: '20%' },
                { text: 'Created Time', datafield: 'createtime', width: '10%' },
                { text: 'Created By', datafield: 'createby', width: '5%' },
                { text: 'Created Time', datafield: 'updatetime', width: '10%' },
                { text: 'Created By', datafield: 'updateby', width: '5%' },
                { text: 'Status', datafield: 'status', width: '5%' }
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
            loadmodules: [
                "js/tags/jquery.tagsinput.min.js",
                "js/switchery/switchery.min.js",
                "js/moment/moment.min.js",
                "js/datepicker/daterangepicker.js",
                "js/editor/bootstrap-wysiwyg.js",
                "js/editor/external/jquery.hotkeys.js",
                "js/editor/external/google-code-prettify/prettify.js",
                "js/select/select2.full.js",
                "js/parsley/parsley.min.js",
                "js/textarea/autosize.min.js",
                "js/autocomplete/jquery.autocomplete.js"
                
            ],
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
        }
    };

    $('.oracle_app_userinfo_username').html(oracle_app.userinfo.username);

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
            if (res.status == 'error') {
                // show model then jump to login
                oracle_app.oracleModal_message.modal('show');
            }
        }
    });

    // ------------------------------------------------------------------------------------
    
    oracle_app.oracleModal_message.on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget) // Button that triggered the modal
        var recipient = button.data('whatever') // Extract info from data-* attributes
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        var modal = $(this)
        modal.find('.modal-title').text('Warning!');
        modal.find('.modal-body').html('- You Session is expired, please login again.');
        modal.find("button:contains('Close')").click(function(){
            window.location = oracle_app.baseurl + 'login';
        });
    });
    oracle_app.oracleModal_comfirm.on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget) // Button that triggered the modal
        var recipient = button.data('whatever') // Extract info from data-* attributes
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        var modal = $(this)
        modal.find('.modal-title').text('Warning!');
        modal.find('.modal-body').html('Confirm to save this information?');
        modal.find("button:contains('Close')").click(function(){
            modal.modal('close');
        });
    });

    // ------------------------------------------------------------------------------------

    oracle_app.nav_link = function() {
        return function() {
            var href = $(this).attr('xhref');
            $(this).parent().siblings().removeClass('current-page');
            $(this).parent().addClass('current-page');

            window.location = oracle_app.baseurl + '#/' + href;

            var controller = href.split("/");

            for (var x in oracle_app[controller[0]][controller[1]].el_remove) {
                $(oracle_app[controller[0]][controller[1]].el_remove[x]).remove();
            }

            if (oracle_app[controller[0]][controller[1]].cache_nav_content.length) {
                $('#nav_content').html('');
                oracle_app[controller[0]][controller[1]].cache_nav_content.appendTo('#nav_content');
                oracle_app[controller[0]][controller[1]].scripts();
                return false;
            }

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
            ajax_load_arr = $.load_ajax_arr(ajax_load_arr, oracle_app[controller[0]][controller[1]].loadmodules, oracle_app.include_path, 'script', 'cache');

            oracle_app.load_content = $.ajax({ url:oracle_app.baseurl + href , dataType: 'html' });
            ajax_load_arr.push(oracle_app.load_content);

            $.when.apply($, ajax_load_arr).done(function(x) {
                oracle_app.load_content.success(function(){
                    var res = oracle_app.return_json_err(oracle_app.load_content.responseText);
                    if (res === false) {
                        oracle_app.nav_content.html(oracle_app.load_content.responseText);
                        
                        setTimeout(function() {
                            oracle_app[controller[0]][controller[1]].scripts();
                            oracle_app[controller[0]][controller[1]].cache_nav_content = $('#oracle_app_'+controller[0]+'_'+controller[1]+'_html');
                        });
                    } else {
                        if (res.status == 'error') { 
                            // show model then jump to login
                            oracle_app.oracleModal_message.modal('show');
                        }
                    }
                });
            });

            return false;
        };

    };

    $(document).on("click", "a.orcle_ajaxload", oracle_app.nav_link());
    $(document).on("click", "#oracle_app_company_view_edit_btn", oracle_app.nav_link());
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
