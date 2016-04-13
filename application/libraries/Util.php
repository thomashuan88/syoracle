<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Util {

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