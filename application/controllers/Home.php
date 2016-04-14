<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

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


	public function __construct() {
		parent::__construct();
		$this->load->model('admin_model');
        $this->load->model('badlogin_model');
		$this->load->model('log_model');
	}

	public function index()
	{

		$more_js = array(
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
		$badlogin_list = $this->badlogin_model->get_all(array(
			"ip_address" => $this->user_ipaddress
		));

        $this->check_ip_is_block($badlogin_list);

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

        	$this->error_backto_login(array("Invalid Password!"));
        }
        
        $this->login_success($userinfo);

	}

    protected function check_ip_is_block($list=array()) {
        $exist = 0;
        $no_exist = 0;
        $bigger_ten = 0;

        foreach ($list as $val) {
            if ($val['username_exist'] == 1) {
                $exist++;
            }
            if ($val['username_exist'] == 2) {
                $no_exist++;
            } 
            if ($exist >= 10) {
                $bigger_ten++;
            }
        }

        if ($no_exist >= 10 || $bigger_ten >= 3) {
            $this->error_backto_login(array("Your IP address is blocked!"));
        }
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
            "ip_address" => $this->user_ipaddress
        ));
        
        $this->session->set_userdata('userinfo', $userinfo);
        
        $this->log_model->insert(
            array(
                'userid' => $userinfo['id'],
                'username' => $userinfo['username'],
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

}
