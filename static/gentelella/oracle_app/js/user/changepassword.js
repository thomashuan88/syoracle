oracle_app.user.changepassword.scripts = function() {

    var oracle_app_user_changepassword_form = $('#oracle_app_user_changepassword_form');


        oracle_app_user_changepassword_form.find('input[name=username]').val(oracle_app.userinfo.username);


    window.Parsley.addAsyncValidator('password_check', function(xhr) {
        window.ParsleyUI.removeError(this,'Oldpassword');
        if(xhr.status == '200') {
            return 200;
        }
        if(xhr.status == '404') {
            response = $.parseJSON(xhr.responseText);
            window.ParsleyUI.addError(this,'Oldpassword',response.error);
        }
    }, oracle_app.baseurl + 'api/user/passwordcheck?username='+oracle_app.userinfo.username);


    oracle_app_user_changepassword_form.parsley();
    oracle_app_user_changepassword_form.on('field:validated', function() {
        window.ParsleyUI.removeError(this,'remote');
    });


    // oracle_app_user_changepassword_form.find('button:reset').click(function(){
    //     $(".side-menu a[xhref='user/view']").trigger('click');
    //     return false;
    // });

    oracle_app_user_changepassword_form.find('.btn').on('click', function() {
        oracle_app_user_changepassword_form.parsley().validate();
    });


    var add_comfirm = false;

    oracle_app_user_changepassword_form.submit(function() {
        var ok = $('.parsley-error').length === 0;
        if (ok && !add_comfirm) {
            swal({
                title: "Change Password",
                text: "Confirm Change Password?",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Comfirm",
                closeOnConfirm: true
            },
            function() {
                add_comfirm = true;
                oracle_app_user_changepassword_form.submit();
            });
            return false;
        } else {
            add_comfirm = false;
        }
        var post_data = {
            username: $(this).find('input[name=username]').val(),
            password: $(this).find('input[name=newpassword]').val(),
        };

        $.post(oracle_app.baseurl + 'api/user/passwordupdate', post_data, function(data){
            if (data.status == 'success') {
                swal("Password Change", "Successfully Change Password.", "");

                $(".top_nav a[xhref='user/changepassword']").trigger('click');

            } else {
                swal("Password Change", "Change Password Fail.", "error");
            }
        }, 'json');

        return false;
    });

};
