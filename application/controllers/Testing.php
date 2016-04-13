<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use \Firebase\JWT\JWT;

class Testing extends CI_Controller {

    public function index()
    {
		$key = "example_key";
		$token = array(
		    "iss" => "http://example.org",
		    "aud" => "http://example.com",
		    "iat" => 1356999524,
		    "nbf" => 1357000000
		);

		$jwt = JWT::encode($token, $key);
		$decoded = JWT::decode($jwt, $key, array('HS256'));

		print_r($decoded);
    }
    
    public function test() {
        $this->predis->hashDel('login_ipaddress_192.168.10.1', "count");
        $this->predis->hashDel('login_ipaddress_192.168.10.1', "username");

        echo base_url();exit;
        $this->load->model('admin_model');

        // echo $this->router->method;

        $this->test2('admin_model', 'insert_admin');

        print_r($this->config->item('hash_key'));
    }

    public function test2($model, $method) {

        print_r($this->$model->$method()); 
    }

    public function testsession() {

        echo FCPATH; exit;
        $this->load->library('session');

        $this->session->set_userdata(array("asasfd"=>"adsads"));
        print_r($_SESSION);
    }
}
