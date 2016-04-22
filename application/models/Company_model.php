<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Company_model extends MY_Model {
    
    public $table_name = 'company'; 
    public $fields = '`id`,  `companyname`,  `description`,  `prefix`,  `joburl`,  `createtime`,  `updatetime`,  `createby`,  `updateby`,  `status`';

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

    public function get_company_list($start_row=0, $rows=20, $order=array(), $cond=array()) {

        $this->db_read->select($this->fields);
        if (!empty($order[0])) $this->db_read->order_by($order[0], $order[1]);

        if (!empty($cond['where'])) {
            $this->do_where($cond['where']);
        }
        if (!empty($cond['like'])) {
            $this->do_like($cond['like']);
        }
        $query = $this->db_read->get($this->table_name, $rows, $start_row);

        $result = array();
        // echo $this->db_read->last_query();exit;
        $result = $query->result_array();

        if (!empty($cond['where'])) {
            $this->do_where($cond['where']);
        }
        if (!empty($cond['like'])) {
            $this->do_like($cond['like']);
        }        

        return array(
            "data" => !empty($result)?$result:array(),
            "records" => $this->db_read->from($this->table_name)->count_all_results()
        );
    }

    public function get_prefix() {
        $this->db_read->group_by("prefix");
        $this->db_read->select('prefix');
        return $this->get_all();
    }

    // 

}