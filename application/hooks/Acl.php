<?php
class Acl {

    private $url_model;
    private $url_method;
    private $CI;
    private $err_msg = array(
        "status" => "error",
        "msg" => "Unauthorized Access!"
    );

    private $no_auth_list = array(
        array("login",""),
        array("home","loginPost"),
        array("logout",""),
    );
 
    function __construct ()
    {
        $this->CI =& get_instance();
        // $this->CI->load->library('session');
        $this->CI->load->library('predis');
        $this->CI->load->library('util');

        $this->url_model = $this->CI->uri->segment(1);
        $this->url_method = $this->CI->uri->segment(2);

        $this->page_type = !empty($this->CI->page_type)?$this->CI->page_type:'';
    }
 
    function auth()
    {

        if (empty($this->CI->from_mycontroller)) {
            return;
        }

        foreach($this->no_auth_list as $key => $val) {
            if ($val[0] == $this->url_model && $val[1] == $this->url_method) {
                return;
            }
        }

        $userinfo = $this->CI->session->userdata("userinfo");
        if (empty($userinfo)) {
            if ($this->page_type == 'ajax') {
                 $this->err_output($this->err_msg);
            } else {
                redirect('login');
            }
        } else {
            $this->CI->userinfo = $userinfo;
            return;
        }

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
                   $this->err_output($this->err_msg);
                }
            }else{
                $this->err_output($this->err_msg);
            }
        }
        else
            $this->err_output($this->err_msg);
    }

    public function err_output($err_msg=array()) {
        if ( $this->page_type == 'ajax' ) {
            $this->CI->util->_echoJson($err_msg);
        } else {
            echo $err_msg; exit;
        }
    }
}