oracle_app.company.add.scripts = function() {

    $('input.flat').iCheck({
        checkboxClass: 'icheckbox_flat-green',
        radioClass: 'iradio_flat-green'
    });
    $('input[name=status]').on('ifChecked', function(event){
        $(this).val('active');
    });
    $('input[name=status]').on('ifUnchecked', function(event){
        $(this).val('inactive');
    });
    
    var oracle_app_company_add_form = $('#oracle_app_company_add_form');

    var validateFront = function() {
        if (true === oracle_app_company_add_form.parsley().isValid()) {
            $('.bs-callout-info').removeClass('hidden');
            $('.bs-callout-warning').addClass('hidden');
        } else {
            $('.bs-callout-info').addClass('hidden');
            $('.bs-callout-warning').removeClass('hidden');
        }
    };

    $.listen('parsley:field:validate', function() {
        validateFront();
    });


    oracle_app_company_add_form.find('.btn').on('click', function() {
        oracle_app_company_add_form.parsley().validate();
        validateFront();
    });


    try {
        hljs.initHighlightingOnLoad();
    } catch (err) {}

    oracle_app_company_add_form.parsley();

    oracle_app_company_add_form.submit(function() {
        var ok = $('.parsley-error').length === 0;
        if (ok) {
            oracle_app.oracleModal_comfirm.modal('show');
            return false;
        }        
        var post_data = {
            companyname: $(this).find('input[name=companyname]').val(),
            description: $(this).find('textarea[name=description]').val(),
            prefix: $(this).find('input[name=prefix]').val(),
            joburl: $(this).find('input[name=joburl]').val(),
            status: $(this).find('input[name=status]').val()
        };
        console.log(post_data);

        return false;
    });

};
