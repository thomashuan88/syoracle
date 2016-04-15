  <div class="">
    <a class="hiddenanchor" id="toregister"></a>
    <a class="hiddenanchor" id="tologin"></a>

    <div id="wrapper">
      <div id="login" class="animate form">
        <section class="login_content">
          <form method="post" action="<?php echo $baseurl; ?>home/loginPost" id="loginform">
            <h1><i class="fa fa-paw" style="font-size: 26px;"></i> Oracle</h1>
            <?php $error_msg = $this->session->userdata('login_error'); ?>
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
oracle_login.oracleModal_message = $('#oracleModal_message').remove();
oracle_login.oracleModal_message.on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    var recipient = button.data('whatever') // Extract info from data-* attributes
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    var modal = $(this)
    modal.find('.modal-title').text('Warning!');
    modal.find('.modal-body').html('- You have to enter reCAPTCHA!');

});
$(function() {
    $('#loginform').on("submit", function() {
        if (!oracle_login.recap) {
            oracle_login.oracleModal_message.modal('show');
            return false;
        }
    });
})

</script>