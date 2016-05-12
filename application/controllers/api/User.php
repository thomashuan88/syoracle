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
class User extends MY_REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['user_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['user_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['user_delete']['limit'] = 50; // 50 requests per hour per user/key

        $this->load->model('User_model');
        $this->usergroup = [
            "1" => "root",
            "2" => "Super User",
            "3" => "Normal User"
        ];
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

        $data = $this->User_model->get_user_list($startRow, $get['pagesize'], array($get['sortdatafield'], $get['sortorder']), $search_cond);


        $data['data'] = $this->format_records($data['data']);

        $result = [[
            'TotalRows' => $data['records'] ,// total records
            'Rows' => empty($data['data'])?array():$data['data']
        ]];

        $this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
    }

    private function list_search() {
        $search_items = array(
            "search_username" => $this->input->get("search_username", true),
            "search_createtime" => $this->input->get("search_createtime", true)
        );
        $this->session->set_userdata("user_list_search", $search_items);

        $cond = array( "where"=>array(), "like"=>array());
        if (!empty($search_items['search_username'])) $cond['like']['username'] = $search_items['search_username'];
        if (!empty($search_items['search_createtime'])) {
            $createtime = explode(" - ", $search_items['search_createtime']);
            $sdate = strtotime($createtime[0].' 00:00:00');
            $edate = strtotime($createtime[1].' 23:59:59');
            $cond['where'] = array(
                "createtime >=" => $sdate,
                "createtime <=" => $edate
            );
        }

        return $cond;
    }

    private function format_records($records=array()) {
        $result = array();
        foreach ($records as $key => $val) {
            $val['status'] = ($val['status'] == '1')?'active':'inactive';
            $val['createtime'] = ($val['createtime'] > 0)?date('Y-m-d h:i:s', $val['createtime']):'';
            $val['updatetime'] = ($val['updatetime'] > 0)?date('Y-m-d h:i:s', $val['updatetime']):'';
            $val['last_logintime'] = ($val['last_logintime'] > 0)?date('Y-m-d h:i:s', $val['last_logintime']):'';
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
            "username" => $post['username'],
            "email" => $post['email'],
            "usergroup" => $post['usergroup'],
            "usergroup_name" => $this->usergroup[$post['usergroup']],
            "updatetime" => time(),
            "updateby" => $this->userinfo['username'],
            "status" => ($post['status'] == 'active')?'1':'2'
        ];

        if (!empty($post['password'])) {
            $data['password'] = md5($post['password']);
        }

        $result = $this->User_model->update($data, array("id"=>$post['id']));
        if ($result) {
            $this->response(["status"=>"success"], 200);
        } else {
            $this->response(["status"=>"error", "sql"=>$this->User_model->db_write->last_query()], 200);
        }
    }

    public function add_post() {
        $post = $this->input->post();

        if (empty($post)) {
            $this->response(["error"=>"Unauthorized Access"], 404);
        }

        $data = [
            "username" => $post['username'],
            "email" => $post['email'],
            "usergroup" => $post['usergroup'],
            "password" =>  md5($post['password'].$this->config->item("hash_salt")),
            "usergroup_name" => $this->usergroup[$post['usergroup']],
            "createtime" => time(),
            "createby" => $this->userinfo['username'],
            "status" => ($post['status'] == 'active')?'1':'2'
        ];

        $result = $this->User_model->insert($data);
        if ($result) {
            $this->response(["status"=>"success"], 200);
        } else {
            $this->response(["status"=>"error", "sql"=>$this->User_model->db_write->last_query()], 200);
        }
    }

    public function username_get() {
        $username = $this->input->get("username", true);
        $currentid = $this->input->get("currentid", true);

        if (empty($username)) {
             $this->response(["error"=>"Unauthorized Access"], 404);
        }
        $data = $this->User_model->get_one(array("username"=>$username));
        if (!empty($currentid)) {
            if (!empty($data) && $data['id'] != $currentid) {
                $this->response(["error"=>"User Name Already Exist"], 404);
            }
            if ($data['status'] == 2 && $data['id'] != $currentid) {
                $this->response(["error"=>"User Name Inactive"], 404);
            }
        } else {
            if (!empty($data)) {
                $this->response(["error"=>"User Name Already Exist"], 404);
            }

        }

        $this->response([], 200);
    }

    public function passwordcheck_get() {
        $oldpassword = $this->input->get("oldpassword", true);
        $username = $this->input->get("username", true);


        if (empty($username)) {
             $this->response(["error"=>"Unauthorized Access"], 404);
        }

        $userinfo = $this->User_model->get_one(
        	array(
        		"username" => $username,
        		"password" => md5($oldpassword.$this->config->item('hash_salt'))
        	)
        );

        if (empty($userinfo)) {
        	$this->response(["error"=>"Invalid Password"], 404);
        }

        $this->response([], 200);
    }

    public function passwordupdate_post() {
        $post = $this->input->post();
        error_log(print_r($post,true));
        if (empty($post)) {
            $this->response(["error"=>"Unauthorized Access"], 404);
        }
        $data = [
        ];

        if (!empty($post['password'])) {
            $data['password'] = md5($post['password'].$this->config->item("hash_salt"));
        }

        $result = $this->User_model->update($data, array("username"=>$post['username']));
        if ($result) {
            $this->response(["status"=>"success"], 200);
        } else {
            $this->response(["status"=>"error", "sql"=>$this->User_model->db_write->last_query()], 200);
        }
    }


}
