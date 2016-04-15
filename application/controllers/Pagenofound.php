<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Pagenofound extends CI_Controller
{

    function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->include_path = base_url('static/gentelella')."/"; // tamplate folder
        $this->load->view('pagenofound');
    }
}