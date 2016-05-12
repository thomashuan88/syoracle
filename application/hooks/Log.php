<?php
class Log {

    private $url_model;
    private $url_method;
    private $is_api;
    private $CI;
    private $err_msg = array(
        "status" => "error",
        "type" => "",
        "msg" => "Unauthorized Access!"
    );

    private $log_list = array(
        array("home","logout"),

        array("user","view"),
        array("user","add"),
        array("user","edit"),
        array("user","changepassword"),

        array("company","view"),
        array("company","add"),
        array("company","edit"),

        array("monitor","head_beat"),
        array("monitor","database"),
        array("monitor","redis"),
        array("monitor","redis_cache"),
        array("monitor","render_panel"),
    );
    private $api_log_list = array(

        array("user","update"),
        array("user","add"),
        array("user","passwordupdate"),
        array("user","list"),

        array("company","update"),
        array("company","add"),
        array("company","list"),

        array("monitor","head_beat_refresh"),
        array("monitor","database_struct"),
        array("monitor","redis"),
        array("monitor","render_panel"),


    );

    function __construct ()
    {
        $this->CI =& get_instance();
        // $this->CI->load->library('session');
        $this->CI->load->library('predis');
        $this->CI->load->library('util');
        $this->CI->load->model('Log_model');

        if($this->CI->uri->segment(1) == "api"){
            $this->is_api = 1;
            $this->url_model = $this->CI->uri->segment(2);
            $this->url_method = $this->CI->uri->segment(3);
        } else {
            $this->url_model = $this->CI->uri->segment(1);
            $this->url_method = $this->CI->uri->segment(2);
        }
        $this->page_type = !empty($this->CI->page_type)?$this->CI->page_type:'';
    }

    function create_log()
    {

        if (empty($this->CI->from_mycontroller)) {
            return;
        }

        if(isset($this->is_api) && $this->is_api == 1 ){
            foreach($this->api_log_list as $key => $val) {
                if ($val[0] == $this->url_model && $val[1] == $this->url_method) {
                    $userinfo = $this->CI->session->userdata("userinfo");

                    $this->CI->Log_model->insert(
                        array(
                            'userid' => $userinfo['id'],
                            'username' => $userinfo['username'],
                            'sessionid' => $this->CI->session->session_id,  //注意，这里是最新生成的sessionId
                            'page' => 'api/'.$this->url_model,
                            'action' => $this->url_method,
                            'ip' => $this->CI->input->ip_address(),     //IP
                            'user_agent' => $this->CI->input->user_agent(),  //浏览器信息
                            'remark' => $this->CI->input->get_request_header('Referer', TRUE),
                            'log_type' => 'action',
                            'time' => time()
                        )
                    );
                }
            }
        } else {
            foreach($this->log_list as $key => $val) {
                if ($val[0] == $this->url_model && $val[1] == $this->url_method) {

                    $userinfo = $this->CI->session->userdata("userinfo");
                    $this->CI->Log_model->insert(
                        array(
                            'userid' => $userinfo['id'],
                            'username' => $userinfo['username'],
                            'sessionid' => $this->CI->session->session_id,  //注意，这里是最新生成的sessionId
                            'page' => $this->url_model,
                            'action' => $this->url_method,
                            'ip' => $this->CI->input->ip_address(),     //IP
                            'user_agent' => $this->CI->input->user_agent(),  //浏览器信息
                            'remark' => $this->CI->input->get_request_header('Referer', TRUE),
                            'log_type' => 'action',
                            'time' => time()
                        )
                    );
                }
            }
        }// end else
    }
}
