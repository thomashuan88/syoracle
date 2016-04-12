<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class loginlog_model extends MY_Model {
    
    public $table_name = 'loginlog'; 

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

}