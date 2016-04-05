<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class home extends CI_Controller {

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
	public function index()
	{
		$this->load->helper('url');

		$viewdata = array();
		$viewdata['aurelia_base'] = base_url()."aurelia/";
		$viewdata['appinfo'] = array(
			"base_url" => base_url()
		);

		$this->load->view('home', $viewdata);
	}

	public function testing() {
		$this->load->model('admin_model');

		$this->test2('admin_model', 'insert_admin');

		print_r($this->config->item('hash_key'));
	}

	public function test2($model, $method) {
		print_r($this->$model->$method()); exit;
	}
}
