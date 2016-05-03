<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitor extends MY_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *      http://example.com/index.php/welcome
     *  - or -
     *      http://example.com/index.php/welcome/index
     *  - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */

    public $page_type;
    
    public function __construct() {
        parent::__construct();

        $this->page_type = 'ajax';
        $this->load->model('Company_model');

        $this->companyname = "";

    }

    public function index() {
         $this->load->view('pagenofound');
    }

    public function head_beat() {

        $this->head_beat_status = "";
        
        $view_data = array();


        $this->load->view('monitor/head_beat', $view_data);
    }


}
