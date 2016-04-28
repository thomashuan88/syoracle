oracle_app.company.add.scripts = function() {
    
    var oracle_app_company_add_form = $('#oracle_app_company_add_form');

    oracle_app_company_add_form.find('#companyname').parsley().addAsyncValidator('unique_companyname', function(xhr) {
        // console.log(xhr); // jQuery Object[ input[name="q"] ]
        // console.log(this.$element); // jQuery Object[ input[name="q"] ]
        // var companyname = $(this.$element).parsley();
        
        window.ParsleyUI.removeError(this,'errorCompanyname');

        if(xhr.status == '200') {
            return 200;
        }
        if(xhr.status == '404') {
            response = $.parseJSON(xhr.responseText);
            window.ParsleyUI.addError(this,'errorCompanyname',response.error);        
        }

    }, oracle_app.baseurl + 'api/company/companyname');
    

    oracle_app_company_add_form.find('input.flat').iCheck({
        checkboxClass: 'icheckbox_flat-green',
        radioClass: 'iradio_flat-green'
    });
    oracle_app_company_add_form.find('input[name=status]').on('ifChecked', function(event){
        $(this).val('active');
    });
    oracle_app_company_add_form.find('input[name=status]').on('ifUnchecked', function(event){
        $(this).val('inactive');
    });

    oracle_app_company_add_form.parsley();
    oracle_app_company_add_form.on('field:validated', function() {
        window.ParsleyUI.removeError(this,'remote');
    });


    // oracle_app_company_add_form.find('.btn').on('click', function() {
    //     oracle_app_company_add_form.parsley().validate();
    // });

   
    var add_company_comfirm = false;

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
            secure_key: $(this).find('input[name=secure_key]').val(),
            status: $(this).find('input[name=status]').val()
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
