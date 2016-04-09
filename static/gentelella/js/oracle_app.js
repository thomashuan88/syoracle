var oracle_app = {};

$(function() {
    oracle_app.baseurl = $('#appinfo').attr("baseurl");

    oracle_app.showloading = function() {
        oracle_app.coverlayer = $('<div id="coverlayer"></div>');
        oracle_app.coverlayer.css({
            'position':'fixed',
            'top':'0',
            'left':'0',
            'background':'rgba(255,255,255,0.6)',
            'z-index':'100',
            'width':'100%',
            'height':'100%'
        });
        oracle_app.coverlayer.append($('#oracle_loading').css);
        oracle_app.coverlayer.appendTo('body');
    };

    oracle_app.removeloading = function() {
        oracle_app.coverlayer.remove();
    };

    $(document).ajaxStart(function(){
        oracle_app.showloading();
    })
    $(document).ajaxStop(function(){
        oracle_app.removeloading();
    });

    // ------------------------------------------------------------------------------------

    $('a.orcle_ajaxload').on("click",  function() {
        var href = $(this).attr('xhref');
        $(this).parent().siblings().removeClass('current-page');
        $(this).parent().addClass('current-page');

        window.location = oracle_app.baseurl+'#/' + href;

        return false;
    });
});