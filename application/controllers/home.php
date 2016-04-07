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

	public $ip_rediskey = 'login_ipaddress_';

	public function __construct() {
		parent::__construct();
		$this->load->model('admin_model');
		$this->load->model('ip_block_model');
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
		$user_ipaddress = $this->input->ip_address();

		if ($user_ipaddress == '0.0.0.0') {
			$this->error_backto_login(array("Your IP address is invalid!"));
		}

		// check ip block list
		$ip_block_list = $this->ip_block_model->get_one(array(
			"ip_address" => $user_ipaddress,
			"status" => 1
		));

		if (!empty($ip_block_list)) {
			$this->error_backto_login(array("Your IP address is blocked!"));
		}

		if (empty($post['username'] || empty($post['password']))) {
			$this->error_backto_login(array("Username and Password cannot empty!"));
		}


        $recaptcha = $this->input->post('g-recaptcha-response');
        if (!empty($recaptcha)) {
            $response = $this->recaptcha->verifyResponse($recaptcha);
            if (isset($response['success']) and $response['success'] === true) {
                
            } else {
            	$error_msg[] = "Captcha Fail!";
            }
        }


        $username = $this->admin_model->get_one(
        	array(
        		"username" => $post['username'], 
        		"status" => 1,
        		"login_count <" => 10
        	)
        );

        if (empty($username)) {
        	$ipcount = $this->predis->hashGet($this->ip_rediskey.$user_ipaddress, "count");
        	$uname = $this->predis->hashGet($this->ip_rediskey.$user_ipaddress, "username");
        	if ($ipcount >= 3) {
        		// add to ip block list
        		$this->ip_block_model->insert(array(
        			"ip_address"=>$user_ipaddress, 
        			"username" => $uname,
        			"createtime" => time()
        		));
		    	$this->admin_model->update(
		    		array("login_count"=> 0), 
		    		array("username"=>$userinfo['username'])
		    	);
        		// remove this redis key
        		$this->predis->hashDel($this->ip_rediskey.$user_ipaddress, "count");
        		$this->predis->hashDel($this->ip_rediskey.$user_ipaddress, "username");
        	} else {
        		$this->predis->hashSet($this->ip_rediskey.$user_ipaddress, "count", $ipcount + 1);
        	}
        	$this->error_backto_login(array("User not exist!"));
        }
        if ($username['login_count'] >= 10) {
        	$this->error_backto_login(array("User Account is block! Please contact IT support!"));
        }

        $userinfo = $this->admin_model->get_one(
        	array(
        		"username" => $post['username'], 
        		"password" => md5($post['password'].$this->hash_salt),
        		"status" => 1,
        		"login_count <" => 10
        	)
        );

        if (empty($userinfo)) {
        	$this->admin_model->update(
        		array("login_count"=>$username['login_count'] + 1), 
        		array("username"=>$username['username'])
        	);
        	if ($username['login_count'] == 9) {
        		$this->predis->hashSet($this->ip_rediskey.$user_ipaddress, "count", 0);
        		$this->predis->hashSet($this->ip_rediskey.$user_ipaddress, "username", $username['username']);
        	}
        	$this->error_backto_login(array("Wrong Password!"));
        }


    	$this->admin_model->update(
    		array("login_count"=> 0), 
    		array("username"=>$userinfo['username'])
    	);

    	$this->session->set_userdata('userinfo', $userinfo);
		
    	redirect("/");

	}

	public function logout() {
		$this->session->unset_userdata('userinfo');
		redirect('login');
	}

	protected function error_backto_login($errmsg=array()) {
    	$this->session->set_userdata('login_error', $errmsg);
    	redirect('login');
	}
}
