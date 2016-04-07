<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ip_block_model extends MY_Model {
    
    public $table_name = 'ip_block'; 

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

}