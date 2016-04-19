  <div class="">
    <a class="hiddenanchor" id="toregister"></a>
    <a class="hiddenanchor" id="tologin"></a>

    <div id="wrapper">
      <div id="login" class="animate form">
        <section class="login_content">
          <form method="post" action="<?php echo $baseurl; ?>home/loginPost" id="loginform">
            <h1><img src="<?php echo $this->include_path; ?>images/ee_logo_small_2.png" style="width: 50%;margin-right: -36px;margin-left: -43px;margin-top: -10px;" /> <span style="color:#F0C493;">Oracle</span></h1>
            <?php $error_msg = $this->session->userdata('login_error'); ?>
            <div id="login_error_msg">
            <?php if (!empty($error_msg)): ?>
                
                <?php foreach($error_msg as $val): ?>
                <div class="alert alert-danger alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                </button>
                <?php echo $val; ?>
                </div>
                <?php endforeach; ?>                    
                

                <?php $this->session->unset_userdata('login_error'); ?>
            <?php endif; ?>
            </div>
            <div>
              <input type="text" class="form-control" placeholder="Username" name="username" required="" />
            </div>
            <div>
              <input type="password" class="form-control" placeholder="Password" name="password" required="" />
            </div>

            <!-- <div class="g-recaptcha" data-sitekey="6LfksRwTAAAAAMjJi1zTZjjtZutFvmkf4AmcNT8q" data-theme="light" data-type="image" ></div> -->
            <div id="load_recaptcha" class="login_recap"></div>
            <script type="text/javascript" src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit&hl=en" async defer></script>

            <div>
              <input type="submit" class="btn btn-default submit" value="Log in" name="submit" />
            </div>
            <div class="clearfix"></div>


              <div class="clearfix"></div>
              <br />
              <div>
                <!-- <h1><i class="fa fa-paw" style="font-size: 26px;"></i> Oracle Admin</h1> -->

                <!-- <p>©2015 All Rights Reserved.Or</p> -->
              </div>
            </div>
          </form>
          <!-- form -->
        </section>
        <!-- content -->
      </div>

    </div>
  </div>

<script>
var oracle_login = {
    recap: false
};

var verifyCallback = function(response) {
    if (response) {
        oracle_login.recap = true;
    }
};
var widgetId1;
var widgetId2;
var onloadCallback = function() {
    // Renders the HTML element with id 'example1' as a reCAPTCHA widget.
    // The id of the reCAPTCHA widget is assigned to 'widgetId1'.

    grecaptcha.render('load_recaptcha', {
      'sitekey' : '<?php echo $this->config->item('recaptcha_site_key'); ?>',
      'callback' : verifyCallback,
      'theme' : 'light'
    });
};

$(function() {
    $('#loginform').on("submit", function() {
        if (!oracle_login.recap) {
            // oracle_login.oracleModal_message.modal('show');
            $('#login_error_msg').html('<div class="alert alert-danger alert-dismissible fade in" role="alert">'+
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>'+
                    '</button>You have to enter reCAPTCHA!</div>');
            return false;
        }
    });
})

</script>