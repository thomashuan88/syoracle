<?php

class MY_Model extends CI_Model {
    public $table_prefix = ''; //表前缀
    public $table_name = '';
    protected $ci = null;
    public $primaryKey = 'id';
    /**
     * 构造函數
     */
    public function __construct() {
        parent::__construct();
        $this->db_write = $this->load->database('write', TRUE);
        $this->db_read = $this->load->database('read', TRUE);
    }

    public function get_all($cond=array(), $limit=0, $offset=0)
    {

        $query = $this->db_read->get_where($this->table_name, $cond, $limit, $offset);
        $result = array();
        $result = $query->result_array();
        return !empty($result)?$result:false;
    }

    public function get_one($cond=array(), $limit=1, $offset=0) {
        $query = $this->db_read->get_where($this->table_name, $cond, $limit, $offset);
        $result = array();
        $result = $query->result_array();
        return !empty($result[0])?$result[0]:false;
    }

    public function update($data=array(), $cond=array()) {
        $this->db_write->where($cond);
        return $this->db_write->update($this->table_name, $data);
    }

    public function insert($data=array()) {
        return $this->db_write->insert($this->table_name, $data);
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
