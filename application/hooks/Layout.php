<?php
class Layout {

    function __construct ()
    {
        $this->CI =& get_instance();
        // $this->CI->load->library('session');
    }
 
    function render()
    {
        // $this->config->set_item('jwt_token', 'item_value'); //set new item for jwt token after login post to login api !!
        $this->CI->load->view('_layout_main', $this->CI->viewdata);
    }
}