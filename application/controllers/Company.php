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
        $this->load->model('Company_model');

    }

    public function index() {
         $this->load->view('pagenofound');
    }

    public function view() {
        
        $view_data = array();
        $company_list_search = $this->session->userdata("company_list_search");
        if (!empty($company_list_search)) {
            $view_data['company_list_search'] = $company_list_search;
        }

        $prefix_data = $this->Company_model->get_prefix();
        $prefixlist = '';
        if (!empty($prefix_data)) {
            
            foreach ($prefix_data as $val) {
                if (!empty($company_list_search['search_prefix'])) {
                    $prefixlist .= '<option selected>'.$val['prefix'].'</option>';
                } else {
                    $prefixlist .= '<option>'.$val['prefix'].'</option>';
                }
                
            }            
        }


        $view_data['prefixlist'] = $prefixlist;

        $this->load->view('company/view', $view_data);
    }

    public function add() {
        $view_data = array(
            "path" => "company_add",
            "title" => "Add Company"
        );
        $this->load->view('company/add', $view_data);
    }

    public function edit() {
        $view_data = array(
            "path" => "company_edit",
            "title" => "Edit Company"
        );
        $this->load->view('company/add', $view_data);
    }
}
