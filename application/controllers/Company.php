<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Company extends MY_Controller {

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
        // $this->load->model('company');

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

    public function view() {
        $view_data = array();

        $this->load->view('company/view', $view_data);
    }

    public function add() {
        $view_data = array();
        $this->load->view('company/add', $view_data);
    }
}
