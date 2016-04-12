function getReady() {
    var deferredReady = $.Deferred();
    $(document).ready(function() {
        deferredReady.resolve();
    });
    return deferredReady.promise();
}

var oracle_app = {};

oracle_app.company = { 
    view:{ 
        loadscripts:[
            "jqgrid/js/i18n/grid.locale-en.js",
            "jqgrid/js/jquery.jqGrid.min.js",
        ],
        loadcss: [
            "jqgrid/css/ui.jqgrid-bootstrap.css"
        ]

    } 
};

oracle_app.return_json_err = function(str) {
    var json;
    try {
        json = $.parseJSON(str);
    } catch (e) {
        json = false;
    }
    return json;
};

oracle_app.nav_content = $('#nav_content');
oracle_app.oracleModal_message = $('#oracleModal_message');


$(function() {

    var appinfo = $('#appinfo');
    oracle_app.baseurl = appinfo.attr("baseurl");
    oracle_app.include_path = appinfo.attr("include-path");

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
        oracle_app.coverlayer.append($('#oracle_loading'));
        oracle_app.coverlayer.appendTo('body');
    };

    oracle_app.removeloading = function() {
        oracle_app.coverlayer.remove();
    };

    $(document).ajaxStart(function() {
        oracle_app.showloading();
    })
    $(document).ajaxStop(function() {
        oracle_app.removeloading();
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
    })

    // ------------------------------------------------------------------------------------

    $('a.orcle_ajaxload').on("click", function() {
        var href = $(this).attr('xhref');
        $(this).parent().siblings().removeClass('current-page');
        $(this).parent().addClass('current-page');

        window.location = oracle_app.baseurl + '#/' + href;

        console.log(oracle_app.baseurl + href);

        oracle_app.load_content = $.ajax({ url: oracle_app.baseurl + href });

        var controller = href.split("/");

        $.getMultiScripts(
            oracle_app[controller[0]][controller[1]].loadscripts, 
            oracle_app.include_path
        ).then(function() {
            var loadcss = oracle_app[controller[0]][controller[1]].loadcss;
            for (var x in loadcss) {
                $("<link/>", {
                   rel: "stylesheet",
                   type: "text/css",
                   href: oracle_app.include_path + loadcss[x]
                }).appendTo("head");
            }
            return oracle_app.load_content;
        })
        .done(function(x) {
            var res = oracle_app.return_json_err(oracle_app.load_content.responseText);
            if (res === false) {
                oracle_app.nav_content.html(oracle_app.load_content.responseText);
            } else {
                if (res.status == 'error') {
                    // show model then jump to login
                    oracle_app.oracleModal_message.modal('show');
                }
            }
        });

        return false;
    });
});

$.getMultiScripts = function(arr, path) {
    var _arr = $.map(arr, function(scr) {
        return $.getScript( (path||"") + scr );
    });

    _arr.push(getReady());

    return $.when.apply($, _arr);
};
