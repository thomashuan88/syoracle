  <div class="">
    <a class="hiddenanchor" id="toregister"></a>
    <a class="hiddenanchor" id="tologin"></a>

    <div id="wrapper">
      <div id="login" class="animate form">
        <section class="login_content">
          <form method="post" action="<?php echo $baseurl; ?>home/loginPost">
            <h1>Login Form</h1>
            <div>
              <input type="text" class="form-control" placeholder="Username" name="username" required="" />
            </div>
            <div>
              <input type="password" class="form-control" placeholder="Password" name="password" required="" />
            </div>
            <div class="login_recap">
            <?php
            echo $widget;
            echo $script;
            ?>
            </div>
            <div>
              <input type="submit" class="btn btn-default submit" value="Log in" name="submit" />
            </div>
            <div class="clearfix"></div>
            <div class="separator">

              <div class="clearfix"></div>
              <br />
              <div>
                <h1><i class="fa fa-paw" style="font-size: 26px;"></i> Oracle Admin</h1>

                <p>©2015 All Rights Reserved. Gentelella Alela! is a Bootstrap 3 template. Privacy and Terms</p>
              </div>
            </div>
          </form>
          <!-- form -->
        </section>
        <!-- content -->
      </div>

    </div>
  </div>