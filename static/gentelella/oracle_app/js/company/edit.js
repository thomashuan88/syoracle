oracle_app.company.edit.scripts = function() {
    
    var oracle_app_company_edit_form = $('#oracle_app_company_edit_form');
    
    if (oracle_app.company.edit.row_data) {
        var row_data = oracle_app.company.edit.row_data;
        oracle_app_company_edit_form.find('input[name=id]').val(row_data.id);
        oracle_app_company_edit_form.find('input[name=companyname]').val(row_data.companyname);
        oracle_app_company_edit_form.find('textarea[name=description]').val(row_data.description);
        oracle_app_company_edit_form.find('input[name=prefix]').val(row_data.prefix);
        oracle_app_company_edit_form.find('input[name=joburl]').val(row_data.joburl);
        if (row_data.status == 'active') {
            oracle_app_company_edit_form.find('#status_active').iCheck('check');
        } else {
            oracle_app_company_edit_form.find('#status_inactive').iCheck('check');
        }
    }

    window.Parsley.addAsyncValidator('unique_companyname', function(xhr) {
        window.ParsleyUI.removeError(this,'errorCompanyname');
        if(xhr.status == '200') {
            return 200;
        }
        if(xhr.status == '404') {
            response = $.parseJSON(xhr.responseText);
            window.ParsleyUI.addError(this,'errorCompanyname',response.error);        
        }
    }, oracle_app.baseurl + 'api/company/companyname?currentid='+row_data.id);

    oracle_app_company_edit_form.find('input.flat').iCheck({
        checkboxClass: 'icheckbox_flat-green',
        radioClass: 'iradio_flat-green'
    });
    oracle_app_company_edit_form.find('input[name=status]').on('ifChecked', function(event){
        $(this).val('active');
    });
    oracle_app_company_edit_form.find('input[name=status]').on('ifUnchecked', function(event){
        $(this).val('inactive');
    });

    oracle_app_company_edit_form.parsley();
    oracle_app_company_edit_form.on('field:validated', function() {
        window.ParsleyUI.removeError(this,'remote');
    });

    var add_company_comfirm = false;
    oracle_app_company_edit_form.submit(function() {
        
        var post_data = {
            id: $(this).find('input[name=id]').val(),
            companyname: $(this).find('input[name=companyname]').val(),
            description: $(this).find('textarea[name=description]').val(),
            prefix: $(this).find('input[name=prefix]').val(),
            joburl: $(this).find('input[name=joburl]').val(),
            secure_key: $(this).find('input[name=secure_key]').val(),
            status: $(this).find('input[name=status]').val()
        };
        // check same or not
        var update_same = true;
        for (var x in post_data) {
            if (post_data[x] != row_data[x]) update_same = false;
        }

        if (update_same) {
            swal("Update Fail", "Company data no change.", "error");
            return false;
        }

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
                oracle_app_company_edit_form.submit();
            });
            return false;
        } else {
            add_company_comfirm = false;
        } 

        $.post(oracle_app.baseurl + 'api/company/update', post_data, function(data){
            if (data.status == 'success') {
                $(".side-menu a[xhref='company/view']").trigger('click');
                // swal({
                //     title: "Update Success",
                //     text: "Company data updated.",
                //     type: "success",
                //     showCancelButton: false,
                //     closeOnConfirm: true
                // },
                // function() {
                //     $(".side-menu a[xhref='company/view']").trigger('click');
                // });
                
            } else {
                swal("Update Fail", "Company data update fail.", "error");
            }
        }, 'json');
 
        return false;
    });

}