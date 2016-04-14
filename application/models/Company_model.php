<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Company_model extends MY_Model {
    
    public $table_name = 'company'; 
    public $fields = '`id`,  `companyname`,  `description`,  `prefix`,  `joburl`,  `createtime`,  `updatetime`,  `createby`,  `updateby`,  `status`';

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

    public function get_company_list($cond=array(), $start_row=0, $rows=20) {


        $this->db_read->select($this->fields);
        $query = $this->db_read->get_where($this->table_name, $cond, $rows, $start_row);

        $result = array();
        $result = $query->result_array();

        return array(
            "data" => !empty($result)?$result:array(),
            "records" => $this->db_read->from($this->table_name)->where($cond)->count_all_results()
        );
    }

    // 

}