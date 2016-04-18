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
        $query = $this->db_read->get_where($this->table_name, $cond, $rows, $start_row);

        $result = array();
        // echo $this->db_read->last_query();exit;
        $result = $query->result_array();

        return array(
            "data" => !empty($result)?$result:array(),
            "records" => $this->db_read->from($this->table_name)->where($cond)->count_all_results()
        );
    }

    // 

}