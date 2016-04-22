<?php

defined('BASEPATH') OR exit('No direct script access allowed');



/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Company extends MY_REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['company_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['company_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['company_delete']['limit'] = 50; // 50 requests per hour per user/key

        $this->load->model('Company_model');
    }


    public function list_get()
    {
        // $this->some_model->update_user( ... );
        $get = $this->input->get();

        if (!isset($get['pagenum']) || !isset($get['pagesize'])) {
            $this->bad_request();
        }

        $search_cond = $this->list_search();

        $startRow = ($get['pagenum']) * (empty($get['pagesize'])?20:$get['pagesize']);

        if (!empty($search_cond['where']) || !empty($search_cond['like'])) {
            $startRow = 0;
        }

        $data = $this->Company_model->get_company_list($startRow, $get['pagesize'], array($get['sortdatafield'], $get['sortorder']), $search_cond);


        $data['data'] = $this->format_records($data['data']);

        $result = [[
            'TotalRows' => $data['records'] ,// total records
            'Rows' => empty($data['data'])?array():$data['data']
        ]];

        $this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
    }

    private function list_search() {
        $search_items = array(
            "search_companyname" => $this->input->get("search_companyname", true),
            "search_createtime" => $this->input->get("search_createtime", true),
            "search_prefix" => $this->input->get("search_prefix", true)
        );
        $this->session->set_userdata("company_list_search", $search_items);

        $cond = array( "where"=>array(), "like"=>array());
        if (!empty($search_items['search_companyname'])) $cond['like']['companyname'] = $search_items['search_companyname'];
        if (!empty($search_items['search_createtime'])) {
            $createtime = explode(" - ", $search_items['search_createtime']);
            $sdate = strtotime($createtime[0].' 00:00:00');
            $edate = strtotime($createtime[1].' 23:59:59');
            $cond['where'] = array(
                "createtime >=" => $sdate,
                "createtime <=" => $edate
            );
        }
        if (!empty($search_items['search_prefix'])) $cond['where']['prefix'] = $search_items['search_prefix'];

        return $cond;
    }

    private function format_records($records=array()) {
        $result = array();
        foreach ($records as $key => $val) {
            $val['status'] = ($val['status'] == '1')?'active':'inactive'; 
            $val['createtime'] = ($val['createtime'] > 0)?date('Y-m-d h:i:s', $val['createtime']):'';
            $val['updatetime'] = ($val['updatetime'] > 0)?date('Y-m-d h:i:s', $val['updatetime']):'';
            $result[$key] = $val;
        }
        return $result;
    }

    public function update_post() {
        $post = $this->input->post();
        if (empty($post)) {
            $this->response(["error"=>"Unauthorized Access"], 404);
        }
        $data = [
            "companyname" => $post['companyname'],
            "description" => $post['description'],
            "prefix" => $post['prefix'],
            "joburl" => $post['joburl'],
            "updatetime" => time(),
            "updateby" => $this->userinfo['username'],
            "status" => ($post['status'] == 'active')?'1':'2'
        ];        
        $result = $this->Company_model->update($data, array("id"=>$post['id']));
        if ($result) {
            $this->response(["status"=>"success"], 200);
        } else {
            $this->response(["status"=>"error", "sql"=>$this->Company_model->db_write->last_query()], 200);
        }
    }

    public function add_post() {
        $post = $this->input->post();

        if (empty($post)) {
            $this->response(["error"=>"Unauthorized Access"], 404);
        }

        $data = [
            "companyname" => $post['companyname'],
            "description" => $post['description'],
            "prefix" => $post['prefix'],
            "joburl" => $post['joburl'],
            "createtime" => time(),
            "createby" => $this->userinfo['username'],
            "status" => ($post['status'] == 'active')?'1':'2'
        ];
            
        $result = $this->Company_model->insert($data);
        if ($result) {
            $this->response(["status"=>"success"], 200);
        } else {
            $this->response(["status"=>"error", "sql"=>$this->Company_model->db_write->last_query()], 200);
        }
    }

    public function companyname_get() {
        $companyname = $this->input->get("companyname", true);
        $currentid = $this->input->get("currentid", true);

        if (empty($companyname)) {
             $this->response(["error"=>"Unauthorized Access"], 404);
        }
        $data = $this->Company_model->get_one(array("companyname"=>$companyname));
        if (!empty($currentid)) {
            if (!empty($data) && $data['id'] != $currentid) {
                $this->response(["error"=>"Company Name Already Exist"], 404);
            } 
            if ($data['status'] == 2 && $data['id'] != $currentid) {
                $this->response(["error"=>"Company Name Inactive"], 404);
            }                           
        } else {
            if (!empty($data)) {
                $this->response(["error"=>"Company Name Already Exist"], 404);
            }  
                    
        }

        $this->response([], 200);
    }

}
