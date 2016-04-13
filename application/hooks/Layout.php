<?php
class Layout {

    function __construct ()
    {
        $this->CI =& get_instance();
        // $this->CI->load->library('session');
    }
 
    function render()
    {
        if (empty($this->CI->viewdata['viewpage'])) return;

        $this->CI->load->view('_layout_main', $this->CI->viewdata);
    }
}