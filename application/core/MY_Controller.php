<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 扩展 Controller 类
 */
// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . 'libraries/REST_Controller.php';

class MY_Controller extends CI_Controller
{

    public $include_path;
    public $viewdata = array();

    function __construct(){
        parent::__construct();
        // header("Content-type:text/html;charset=utf-8");
        date_default_timezone_set('Asia/Shanghai');

        /*
        * 获取系统信息
        * 查看缓存中是否存在，不存在则访问数据库
        * **/
        // $this->site = $this->mp_cache->get('site');
        // if($this->site === false){
        //     $this->site = $this->setting->group('site');
        //     $this->mp_cache->write($this->site,'siteSetting');
        // }
        //验证信息信息是否存在
        // if($this->site){
        //     //是否关闭站点
        //     if(strtolower($this->site['site_status']) != 'open'){
        //         show_error($this->site['site_status_text'], 500 ,'网站已关闭/The website closed');
        //     }
        // }else{
        //     show_error('未获取到系统配置。/ The config not exist', 500 ,'系统配置不存在/No Config');
        // }
        $this->from_mycontroller = 'yes'; // prevent testing controller block by acl hook
        $this->load->library('util');
        $this->include_path = base_url('static/gentelella')."/"; // tamplate folder
        $this->viewdata['include_path'] = base_url('static/gentelella')."/";
        $this->viewdata['baseurl'] = base_url();
        $this->base_url = base_url();

        $this->hash_salt = $this->config->item('hash_salt');
    }

    protected function more_jscss_toString($list=array(), $type='') {
        $result = array();
        foreach ($list as $key => $val) {
            if ($type=="js") {
                $result[] = '<script src="'.$this->viewdata['include_path'].$val.'"></script>';
            } else {
                $result[] = '<link rel="stylesheet" type="text/css" href="'.$this->viewdata['include_path'].$val.'" />';
            }
        }

        return implode("", $result);
    }

}

class MY_REST_Controller extends REST_Controller {
    
    function __construct() {
        parent::__construct();
        $this->from_mycontroller = 'yes';
        $this->page_type = 'ajax';
    }

    protected function bad_request($msg='Bad Request!') {
        $res = [
            "status" => "error",
            "message" => $msg
        ];
        $this->response($res, REST_Controller::HTTP_CREATED);
    }
}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */