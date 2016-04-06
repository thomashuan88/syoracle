<div class="container body">


  <div class="main_container">

    <div class="col-md-3 left_col">
      <div class="left_col scroll-view">

        <div class="navbar nav_title" style="border: 0;">
          <a href="index.html" class="site_title"><i class="fa fa-paw"></i> <span>Oracle Admin</span></a>
        </div>
        <div class="clearfix"></div>

        <!-- menu prile quick info -->
        <div class="profile">
          <div class="profile_pic">
            <img src="<?php echo $this->include_path; ?>images/img.jpg" alt="..." class="img-circle profile_img">
          </div>
          <div class="profile_info">
            <span>Welcome,</span>
            <h2>John Doe</h2>
          </div>
        </div>
        <!-- /menu prile quick info -->

        <br />

        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

          <div class="menu_section">
            <h3>General</h3>
            <ul class="nav side-menu">
              <li><a><i class="fa fa-home"></i> Company Management <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu" style="display: none">
                  <li><a href="index.html">View Company</a>
                  </li>
                  <li><a href="index2.html">Add Company</a>
                  </li>
                </ul>
              </li>
              <li><a><i class="fa fa-edit"></i> Action <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu" style="display: none">
                  <li><a href="form_advanced.html">Head Beat</a>
                  </li>
                  <li><a href="form_validation.html">Database</a>
                  </li>
                  <li><a href="form_wizards.html">Redis</a>
                  </li>

                </ul>
              </li>

            </ul>
          </div>


        </div>
        <!-- /sidebar menu -->

        <!-- /menu footer buttons -->
        <div class="sidebar-footer hidden-small">
          <a data-toggle="tooltip" data-placement="top" title="Settings">
            <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
          </a>
          <a data-toggle="tooltip" data-placement="top" title="FullScreen">
            <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
          </a>
          <a data-toggle="tooltip" data-placement="top" title="Lock">
            <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
          </a>
          <a data-toggle="tooltip" data-placement="top" title="Logout">
            <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
          </a>
        </div>
        <!-- /menu footer buttons -->
      </div>
    </div>

    <!-- top navigation -->
    <div class="top_nav">

      <div class="nav_menu">
        <nav class="" role="navigation">
          <div class="nav toggle">
            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
          </div>

          <ul class="nav navbar-nav navbar-right">
            <li class="">
              <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                <img src="<?php echo $this->include_path; ?>images/img.jpg" alt="">John Doe
                <span class=" fa fa-angle-down"></span>
              </a>
              <ul class="dropdown-menu dropdown-usermenu animated fadeInDown pull-right">
                <li><a href="javascript:;">  Profile</a>
                </li>
                <li>
                  <a href="javascript:;">
                    <span class="badge bg-red pull-right">50%</span>
                    <span>Settings</span>
                  </a>
                </li>
                <li>
                  <a href="javascript:;">Help</a>
                </li>
                <li><a href="login.html"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
                </li>
              </ul>
            </li>

            <li role="presentation" class="dropdown">
              <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-envelope-o"></i>
                <span class="badge bg-green">6</span>
              </a>
              <ul id="menu1" class="dropdown-menu list-unstyled msg_list animated fadeInDown" role="menu">
                <li>
                  <a>
                    <span class="image">
                                      <img src="<?php echo $this->include_path; ?>images/img.jpg" alt="Profile Image" />
                                  </span>
                    <span>
                                      <span>John Smith</span>
                    <span class="time">3 mins ago</span>
                    </span>
                    <span class="message">
                                      Film festivals used to be do-or-die moments for movie makers. They were where...
                                  </span>
                  </a>
                </li>
                <li>
                  <a>
                    <span class="image">
                                      <img src="<?php echo $this->include_path; ?>images/img.jpg" alt="Profile Image" />
                                  </span>
                    <span>
                                      <span>John Smith</span>
                    <span class="time">3 mins ago</span>
                    </span>
                    <span class="message">
                                      Film festivals used to be do-or-die moments for movie makers. They were where...
                                  </span>
                  </a>
                </li>
                <li>
                  <a>
                    <span class="image">
                                      <img src="<?php echo $this->include_path; ?>images/img.jpg" alt="Profile Image" />
                                  </span>
                    <span>
                                      <span>John Smith</span>
                    <span class="time">3 mins ago</span>
                    </span>
                    <span class="message">
                                      Film festivals used to be do-or-die moments for movie makers. They were where...
                                  </span>
                  </a>
                </li>
                <li>
                  <a>
                    <span class="image">
                                      <img src="<?php echo $this->include_path; ?>images/img.jpg" alt="Profile Image" />
                                  </span>
                    <span>
                                      <span>John Smith</span>
                    <span class="time">3 mins ago</span>
                    </span>
                    <span class="message">
                                      Film festivals used to be do-or-die moments for movie makers. They were where...
                                  </span>
                  </a>
                </li>
                <li>
                  <div class="text-center">
                    <a href="inbox.html">
                      <strong>See All Alerts</strong>
                      <i class="fa fa-angle-right"></i>
                    </a>
                  </div>
                </li>
              </ul>
            </li>

          </ul>
        </nav>
      </div>

    </div>
    <!-- /top navigation -->


    <!-- page content -->
    <div class="right_col" role="main">



      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="dashboard_graph">

            <div class="row x_title">
              <div class="col-md-6">
                <h3>Network Activities <small>Graph title sub-title</small></h3>
              </div>
              <div class="col-md-6">
                <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
                  <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                  <span>December 30, 2014 - January 28, 2015</span> <b class="caret"></b>
                </div>
              </div>
            </div>

            <div class="col-md-9 col-sm-9 col-xs-12">
              <div id="placeholder33" style="height: 260px; display: none" class="demo-placeholder"></div>
              <div style="width: 100%;">
                <div id="canvas_dahs" class="demo-placeholder" style="width: 100%; height:270px;"></div>
              </div>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12 bg-white">
              <div class="x_title">
                <h2>Top Campaign Performance</h2>
                <div class="clearfix"></div>
              </div>

              <div class="col-md-12 col-sm-12 col-xs-6">
                <div>
                  <p>Facebook Campaign</p>
                  <div class="">
                    <div class="progress progress_sm" style="width: 76%;">
                      <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="80"></div>
                    </div>
                  </div>
                </div>
                <div>
                  <p>Twitter Campaign</p>
                  <div class="">
                    <div class="progress progress_sm" style="width: 76%;">
                      <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="60"></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-12 col-sm-12 col-xs-6">
                <div>
                  <p>Conventional Media</p>
                  <div class="">
                    <div class="progress progress_sm" style="width: 76%;">
                      <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="40"></div>
                    </div>
                  </div>
                </div>
                <div>
                  <p>Bill boards</p>
                  <div class="">
                    <div class="progress progress_sm" style="width: 76%;">
                      <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="50"></div>
                    </div>
                  </div>
                </div>
              </div>

            </div>

            <div class="clearfix"></div>
          </div>
        </div>

      </div>
      <br />


      <!-- footer content -->

      <footer>
        <div class="copyright-info">
          <p class="pull-right">Gentelella - Bootstrap Admin Template by <a href="https://colorlib.com">Colorlib</a>  
          </p>
        </div>
        <div class="clearfix"></div>
      </footer>
      <!-- /footer content -->
    </div>
    <!-- /page content -->

  </div>

</div>

<div id="custom_notifications" class="custom-notifications dsp_none">
  <ul class="list-unstyled notifications clearfix" data-tabbed_notifications="notif-group">
  </ul>
  <div class="clearfix"></div>
  <div id="notif-group" class="tabbed_notifications"></div>
</div>


