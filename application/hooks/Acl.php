<?php
class Acl {

    private $url_model;
    private $url_method;
    private $CI;
    private $err_msg = array(
        "status" => "error",
        "msg" => "Unauthorized Access!"
    );
 
    function __construct ()
    {
        $this->CI =& get_instance();
        // $this->CI->load->library('session');
        $this->CI->load->library('predis');
        $this->CI->load->library('util');

        $this->url_model = $this->CI->uri->segment(1);
        $this->url_method = $this->CI->uri->segment(2);
    }
 
    function auth()
    {
        // $this->config->set_item('jwt_token', 'item_value'); //set new item for jwt token after login post to login api !!
        if (empty($this->url_model)) return;
        $user = $this->CI->config->item('jwt_token');
        if(empty($user)) {
            $user = new stdClass();
            $user->status = 'ADMIN';
        }

        $this->CI->load->config('acl');
        $AUTH = $this->CI->config->item('AUTH');

        if(in_array($user->status, array_keys($AUTH))){
            $controllers = !empty($AUTH[$user->status])?$AUTH[$user->status]:array();

            if(in_array($this->url_model, array_keys($controllers))){
                
                if(!in_array($this->url_method, $controllers[$this->url_model])){
                   $this->CI->util->_echoJson($this->err_msg);
                }
            }else{
                $this->CI->util->_echoJson($this->err_msg);
            }
        }
        else
            $this->CI->util->_echoJson($this->err_msg);
    }
}