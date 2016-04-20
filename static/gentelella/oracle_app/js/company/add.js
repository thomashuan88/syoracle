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

    window.Parsley.addAsyncValidator('unique_companyname', function(xhr) {
        // console.log(xhr); // jQuery Object[ input[name="q"] ]
        // console.log(this.$element); // jQuery Object[ input[name="q"] ]
        var companyname = $(this.$element).parsley();
        window.ParsleyUI.removeError(companyname,'errorCompanyname');
        window.ParsleyUI.removeError(companyname,'remote');

        if(xhr.status == '200') {
            return 200;
        }
        if(xhr.status == '404') {
            response = $.parseJSON(xhr.responseText);
            window.ParsleyUI.addError(companyname,'errorCompanyname',response.error);        
        }

        // return false;
    }, oracle_app.baseurl + 'api/company/companyname');
    
    var add_company_comfirm = false;
    var oracle_app_company_add_form = $('#oracle_app_company_add_form');
    oracle_app_company_add_form.parsley();

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

    

    oracle_app_company_add_form.submit(function() {
        var ok = $('.parsley-error').length === 0;
        if (ok && !add_company_comfirm) {
            swal({
                title: "Save Company Information",
                text: "Confirm to save this information?",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Comfirm",
                closeOnConfirm: true
            },
            function() {
                add_company_comfirm = true;
                oracle_app_company_add_form.submit();
            });
            return false;
        } else {
            add_company_comfirm = false;
        } 
        var post_data = {
            companyname: $(this).find('input[name=companyname]').val(),
            description: $(this).find('textarea[name=description]').val(),
            prefix: $(this).find('input[name=prefix]').val(),
            joburl: $(this).find('input[name=joburl]').val(),
            status: ($(this).find('input[name=status]').val() == 'active')?'1':'2'
        };

        $.post(oracle_app.baseurl + 'api/company/add', post_data, function(data){
            if (data.status == 'success') {
                swal({
                    title: "Saved Success",
                    text: "Company data saved.",
                    type: "success",
                    showCancelButton: false,
                    closeOnConfirm: true
                },
                function() {
                    $(".side-menu a[xhref='company/view']").trigger('click');
                });
                
            } else {
                swal("Saved Fail", "Company data saved fail.", "error");
            }
        }, 'json');
 
        return false;
    });

};
