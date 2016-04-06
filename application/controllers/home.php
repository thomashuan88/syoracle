<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class home extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct() {
		parent::__construct();
	}

	public function index()
	{
		$more_js = array(
			"js/progressbar/bootstrap-progressbar.min.js",
			"js/nicescroll/jquery.nicescroll.min.js",
			"js/icheck/icheck.min.js",
			"js/moment/moment.min.js",
			"js/datepicker/daterangepicker.js",
			"js/chartjs/chart.min.js",
			"js/custom.js"
		);

		$login_data['baseurl'] = base_url();

		$this->viewdata['more_css'] = '<link href="'.$this->include_path.'css/custom2.css" rel="stylesheet">';
		$this->viewdata['more_js'] = $this->more_jscss_toString($more_js, 'js');
		$this->viewdata['body_attr'] = 'class="nav-md"';
		$this->viewdata['viewpage'] = 'main';
		$this->viewdata['content_main'] = $this->load->view($this->viewdata['viewpage'],$login_data, true);
	}

	public function login() {

		$login_data['widget'] = $this->recaptcha->getWidget();
		$login_data['script'] = $this->recaptcha->getScriptTag();
		$login_data['baseurl'] = base_url();

		$more_css = array('css/custom_login.css');

		$this->viewdata['more_css'] = $this->more_jscss_toString($more_css, 'css');
		$this->viewdata['more_js'] = '';
		$this->viewdata['viewpage'] = 'login/login';
		$this->viewdata['content_main'] = $this->load->view($this->viewdata['viewpage'], $login_data, true);
		
	}

	public function loginPost() {

		$error_msg = array();
		$post = $this->input->post();


        $recaptcha = $this->input->post('g-recaptcha-response');
        if (!empty($recaptcha)) {
            $response = $this->recaptcha->verifyResponse($recaptcha);
            if (isset($response['success']) and $response['success'] === true) {
                
            } else {
            	$error_msg[] = "Captcha Fail!";
            }
        }
        if (!empty($error_msg)) {
        	$this->session->set_userdata('login_error', $error_msg);
        	redirect('login');
        } else {
        	$this->session->set_userdata("userid", 1);
        	redirect('/');
        }
		
		// validate input


		// add user to session
	}

}
