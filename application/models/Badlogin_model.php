<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Badlogin_model extends MY_Model {
    
    public $table_name = 'badlogin'; 
    public $fields = '`id`, `ip_address`, `login_name`, `username_exist`, `createtime`';

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

}