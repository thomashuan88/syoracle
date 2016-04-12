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
    public $user_ipaddress;
    public $login_start_time;
    public $userblock_time;

	public function __construct() {
		parent::__construct();
		$this->load->model('admin_model');
        $this->load->model('ip_block_model');
        $this->load->model('badlogin_model');
		$this->load->model('loginlog_model');
	}

	public function index()
	{

		$more_js = array(
            "js/require.js",
			"js/progressbar/bootstrap-progressbar.min.js",
			"js/nicescroll/jquery.nicescroll.min.js",
            "js/icheck/icheck.min.js",
			"js/custom.js",
            "js/oracle_app.js"
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

    public function logout() {
        $this->session->unset_userdata('userinfo');
        redirect('login');
    }

	public function loginPost() {

		$error_msg = array();
		$post = $this->input->post();
		$this->user_ipaddress = $this->input->ip_address();

        $this->login_start_time = $this->session->userdata('login_start_time');
        if (empty($this->login_start_time)) {
            $this->login_start_time = time();
            $this->session->set_userdata('login_start_time', $this->login_start_time);
        }

        $this->userblock_time = $this->session->userdata('userblock_time');


        $recaptcha = $this->input->post('g-recaptcha-response');
        if (!empty($recaptcha)) {
            $response = $this->recaptcha->verifyResponse($recaptcha);
            if (isset($response['success']) and $response['success'] === true) {
                
            } else {
                $this->error_backto_login(array("Captcha Fail!"));
            }
        }

		if ($this->user_ipaddress == '0.0.0.0') {
			$this->error_backto_login(array("Your IP address is invalid!"));
		}


		// check ip block list
		$ip_block_list = $this->ip_block_model->get_one(array(
			"ip_address" => $this->user_ipaddress,
			"status" => 1
		));

		if (!empty($ip_block_list)) {
			$this->error_backto_login(array("Your IP address is blocked!"));
		}

		if (empty($post['username'] || empty($post['password']))) {
			$this->error_backto_login(array("Username and Password cannot empty!"));
		}

        $username = $this->admin_model->get_one(
        	array(
        		"username" => $post['username'], 
        		"status" => 1
        	)
        );

        if (empty($username)) {
            $this->badlogin_model->insert(array(
                "ip_address"=>$this->user_ipaddress, 
                "login_name" => $post['username'],
                "username_exist" => '2',
                "createtime" => time()
            ));
            $this->check_ip_login();
            $this->error_backto_login(array("Invalid Username!"));
        } else {
            $this->badlogin_model->insert(array(
                "ip_address"=>$this->user_ipaddress, 
                "login_name" => $post['username'],
                "username_exist" => '1',
                "createtime" => time()
            ));
        }

        if ($username['login_count'] >= 10) {
            $this->check_ip_login();
            $this->error_backto_login(array("User Account is inactive! Please contact IT support!"));
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
                $this->session->set_userdata('userblock_time', time());
        	}
            $this->check_ip_login();
        	$this->error_backto_login(array("Invalid Password!"));
        }
        
        $this->check_ip_login();
        $this->login_success($userinfo);

	}



    protected function login_success($userinfo=array()) {
        $this->admin_model->update(
            array(
                "login_count"=> 0, 
                "last_logintime"=>time()
            ), 
            array("username"=>$userinfo['username'])
        );

        // del badlogin where createtime >= login_start_time

        $this->badlogin_model->del(array(
            "ip_address" => $this->user_ipaddress,
            "createtime >=" => $this->login_start_time
        ));
        
        $this->session->unset_userdata('userblock_time');
        $this->session->unset_userdata('login_start_time');
        $this->session->set_userdata('userinfo', $userinfo);
        
        $this->loginlog_model->insert(
            array(
                'admin_id' => $userinfo['id'],
                'admin_name' => $userinfo['username'],
                'sessionid' => $this->session->session_id,  //注意，这里是最新生成的sessionId
                'page' => 'login',
                'action' => 'index',
                'ip' => $this->input->ip_address(),     //IP
                'user_agent' => $this->input->user_agent(),  //浏览器信息
                'remark' => $this->input->get_request_header('Referer', TRUE),
                'log_type' => 'login',
                'time' => time()
            )
        );

        redirect("/");
    }

	protected function error_backto_login($errmsg=array()) {
    	$this->session->set_userdata('login_error', $errmsg);
    	redirect('login');
	}

    protected function check_ip_login() {
        $badlogin_data = $this->badlogin_model->get_all(array(
            "ip_address" => $this->user_ipaddress,
            "createtime >=" => $this->login_start_time
        ));
        // echo '<pre>'; print_r($badlogin_data);exit;
        $wrong_username_pwd_count = 0;
        $after_acct_block_count = 0;

        foreach ($badlogin_data as $key=>$val) {
            if ($val['username_exist'] == 2) {
                $wrong_username_pwd_count ++;

            }
            if (!empty($this->userblock_time) && $val['createtime'] >= $this->userblock_time) {
                $after_acct_block_count ++;
            }
        }

        $block_already = false;
        if ($wrong_username_pwd_count > 9) {
            $this->ip_block_model->insert(array(
                "ip_address"=>$this->user_ipaddress, 
                "createtime" => time()
            ));

            $block_already = true;
        }

        if ($after_acct_block_count > 2 && !$block_already) {
            $this->ip_block_model->insert(array(
                "ip_address"=>$this->user_ipaddress, 
                "createtime" => time()
            ));
        }
    }
}
