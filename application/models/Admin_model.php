<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_model extends MY_Model {
    
    public $table_name = 'admin'; 
    public $fields = '`id`, `userid`, `username`, `password`, `email`, `usergroup`, `usergroup_name`, `login_count`, `last_logintime`, `status`, `updatetime`, `updateby`';

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

    public function insert_admin() {
        $data = array(
            "username" => "admin",
            "password" => md5("123qwe".$this->config->item("hash_salt")),
            "salt" => $this->config->item("hash_salt"),
            "email" => "thomashuan88@gmail.com",
            "createtime" => time()
        );

        return $this->db_write->insert($this->table_name, $data);
    }

}

