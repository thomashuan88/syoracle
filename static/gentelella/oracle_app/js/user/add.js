oracle_app.user.add.scripts = function() {
    
    var oracle_app_user_add_form = $('#oracle_app_user_add_form');

    oracle_app_user_add_form.find('#username').parsley().addAsyncValidator('unique_username', function(xhr) {
        // console.log(xhr); // jQuery Object[ input[name="q"] ]
        // console.log(this.$element); // jQuery Object[ input[name="q"] ]
        // var companyname = $(this.$element).parsley();
        
        window.ParsleyUI.removeError(this,'errorUsername');

        if(xhr.status == '200') {
            return 200;
        }
        if(xhr.status == '404') {
            response = $.parseJSON(xhr.responseText);
            window.ParsleyUI.addError(this,'errorUsername',response.error);        
        }

    }, oracle_app.baseurl + 'api/user/username');
    

    oracle_app_user_add_form.find('input.flat').iCheck({
        checkboxClass: 'icheckbox_flat-green',
        radioClass: 'iradio_flat-green'
    });
    oracle_app_user_add_form.find('input[name=status]').on('ifChecked', function(event){
        $(this).val('active');
    });
    oracle_app_user_add_form.find('input[name=status]').on('ifUnchecked', function(event){
        $(this).val('inactive');
    });

    oracle_app_user_add_form.parsley();
    oracle_app_user_add_form.on('field:validated', function() {
        window.ParsleyUI.removeError(this,'remote');
    });

    oracle_app_user_add_form.find('button:reset').click(function(){
        $(".side-menu a[xhref='user/view']").trigger('click');
        return false;
    });

    // oracle_app_user_add_form.find('.btn').on('click', function() {
    //     oracle_app_user_add_form.parsley().validate();
    // });

   
    var add_comfirm = false;

    oracle_app_user_add_form.submit(function() {
        var ok = $('.parsley-error').length === 0;
        if (ok && !add_comfirm) {
            swal({
                title: "Save User",
                text: "Confirm to save this information?",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Comfirm",
                closeOnConfirm: true
            },
            function() {
                add_comfirm = true;
                oracle_app_user_add_form.submit();
            });
            return false;
        } else {
            add_comfirm = false;
        } 
        var post_data = {
            username: $(this).find('input[name=username]').val(),
            password: $(this).find('input[name=password]').val(),
            usergroup: $(this).find('select[name=usergroup]').val(),
            email: $(this).find('input[name=email]').val(),
            status: $(this).find('input[name=status]').val()
        };

        $.post(oracle_app.baseurl + 'api/user/add', post_data, function(data){
            if (data.status == 'success') {
                $(".side-menu a[xhref='user/view']").trigger('click');
            } else {
                swal("Saved Fail", "Company data saved fail.", "error");
            }
        }, 'json');
 
        return false;
    });

};
