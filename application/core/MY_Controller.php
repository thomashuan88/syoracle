<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 扩展 Controller 类
 */


class MY_Controller extends CI_Controller
{
    function __construct(){
        parent::__construct();
        header("Content-type:text/html;charset=utf-8");
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
    }


    /*
     * json 方式输出
     *
     * **/
    public function _echoJson($arr){
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
        header("Content-type: application/json");
        echo json_encode($arr);
        exit();
    }

    public function reset_redis_key($rkey='', $model='', $method='', $param=array()) {
        $data = json_decode($this->predis->get($rkey),true);
        if (empty($data)) {
            $data = $this->$model->$method($param);
            $this->predis->set($rkey,json_encode($data));
        }
        return $data;
    }
}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */