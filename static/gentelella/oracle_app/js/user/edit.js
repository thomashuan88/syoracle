oracle_app.user.edit.scripts = function() {
    
    var oracle_app_user_edit_form = $('#oracle_app_user_edit_form');
    
    if (oracle_app.user.edit.row_data) {
        var row_data = oracle_app.user.edit.row_data;
        oracle_app_user_edit_form.find('input[name=id]').val(row_data.id);
        oracle_app_user_edit_form.find('input[name=username]').val(row_data.username);
        oracle_app_user_edit_form.find('input[name=email]').val(row_data.email);
        oracle_app_user_edit_form.find('input[name=password]').val(row_data.password);
        oracle_app_user_edit_form.find('select[name=usergroup]').val(row_data.usergroup);
        if (row_data.status == 'active') {
            oracle_app_user_edit_form.find('#status_active').iCheck('check');
        } else {
            oracle_app_user_edit_form.find('#status_inactive').iCheck('check');
        }
    }

    window.Parsley.addAsyncValidator('unique_username', function(xhr) {
        window.ParsleyUI.removeError(this,'errorUsername');
        if(xhr.status == '200') {
            return 200;
        }
        if(xhr.status == '404') {
            response = $.parseJSON(xhr.responseText);
            window.ParsleyUI.addError(this,'errorUsername',response.error);        
        }
    }, oracle_app.baseurl + 'api/user/username?currentid='+row_data.id);

    oracle_app_user_edit_form.find('input.flat').iCheck({
        checkboxClass: 'icheckbox_flat-green',
        radioClass: 'iradio_flat-green'
    });
    oracle_app_user_edit_form.find('input[name=status]').on('ifChecked', function(event){
        $(this).val('active');
    });
    oracle_app_user_edit_form.find('input[name=status]').on('ifUnchecked', function(event){
        $(this).val('inactive');
    });

    oracle_app_user_edit_form.parsley();
    oracle_app_user_edit_form.on('field:validated', function() {
        window.ParsleyUI.removeError(this,'remote');
    });

    oracle_app_user_edit_form.find('button:reset').click(function(){
        $(".side-menu a[xhref='user/view']").trigger('click');
        return false;
    });

    var add_comfirm = false;
    oracle_app_user_edit_form.submit(function() {
        
        var post_data = {
            id: $(this).find('input[name=id]').val(),
            username: $(this).find('input[name=username]').val(),
            email: $(this).find('input[name=email]').val(),
            password: $(this).find('input[name=password]').val(),
            usergroup: $(this).find('select[name=usergroup]').val(),
            status: $(this).find('input[name=status]').val()
        };
        // check same or not
        var update_same = true;
        for (var x in post_data) {
            if (post_data[x] != row_data[x]) update_same = false;
        }

        if (update_same) {
            swal("Update Fail", "User data no change.", "error");
            return false;
        }

        var ok = $('.parsley-error').length === 0;
        if (ok && !add_comfirm) {
            swal({
                title: "Save User Information",
                text: "Confirm to save this information?",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Comfirm",
                closeOnConfirm: true
            },
            function() {
                add_comfirm = true;
                oracle_app_user_edit_form.submit();
            });
            return false;
        } else {
            add_comfirm = false;
        } 

        $.post(oracle_app.baseurl + 'api/user/update', post_data, function(data){
            if (data.status == 'success') {
                $(".side-menu a[xhref='user/view']").trigger('click');
            } else {
                swal("Update Fail", "User data update fail.", "error");
            }
        }, 'json');
 
        return false;
    });

}