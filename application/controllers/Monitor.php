<?php
defined('BASEPATH') OR exit('No direct script access allowed');


use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;

class Monitor extends MY_Controller {


    public $page_type;
    
    public function __construct() {
        parent::__construct();

        $this->page_type = 'ajax';
        $this->load->model('Company_model');


    }

    public function index() {
         $this->load->view('Pagenofound');
    }

    public function heart_beat($companyname='') {

        $this->head_beat_url = "/oracle/heartbeat";
        $this->head_beat_status = "";
        $this->head_beat_token = array(
            "iss" => "Heartbeat",
            "aud" => "syoracle",
            "iat" => time()
        );

        if ($companyname != "") {
            $this->Company_model->db_read->like("companyname", $companyname);
        }
        $this->head_beat_urls = $this->gen_head_beat_urls();

        $this->client = new Client();
        $promises = [];

        foreach ($this->head_beat_urls as $val) {
            $token = JWT::encode($this->head_beat_token, $val['skey']);
            $promises[] = $this->client->requestAsync('GET', $val['url'].$this->head_beat_url, ["headers"=>["Authorization"=>$token]]);
        }

        GuzzleHttp\Promise\settle($promises)->then(function ($responses) {
            $block = "";

            $result = [];
            foreach ($responses as $key => $response) {

                // if ($key % 2 == 0) {
                //     $block .= '<div class="clearfix"></div>';
                // }
                $block_data = [];
                if ($response['state'] != 'fulfilled') {
                    $error = $response['reason']->getHandlerContext(); 
                    // output inactive
                    $block_data['status'] = 'inactive';
                    $block_data['companyname'] = $this->head_beat_urls[$key]['companyname'];
                    $block_data['cid'] = $this->head_beat_urls[$key]['cid'];
                    $block_data['head_beat_status'] = '<i class="fa fa-minus-square" style="color:red"></i> Inactive';
                    $block_data['error_msg'] = $error['error'];
                    $block .= $this->load->view("monitor/Heart_beat_block", $block_data, true);
            
                    continue;
                }

                ob_start();

                echo $response['value']->getBody();
                $output = ob_get_contents();

                ob_end_clean();
                $result = json_decode($output, true);

                if (!empty($result)) {
                    $block_data['status'] = 'active';
                    $block_data['companyname'] = $this->head_beat_urls[$key]['companyname'];
                    $block_data['cid'] = $this->head_beat_urls[$key]['cid'];
                    $block_data['head_beat_status'] = '<i class="fa fa-check-square" style="color:green"></i> Active';
                    $block_data['heart_beat'] = $result;

                    $block .= $this->load->view("monitor/Heart_beat_block", $block_data, true);                    
                } else {
                    $block_data['companyname'] = $this->head_beat_urls[$key]['companyname'];
                    $block_data['cid'] = $this->head_beat_urls[$key]['cid'];
                    $block_data['head_beat_status'] = '<i class="fa fa-minus-square" style="color:red"></i> Inactive';
                    $block_data['error_msg'] = "Wrong URL";
                    $block_data['status'] = "inactive";
                    $block .= $this->load->view("monitor/Heart_beat_block", $block_data, true);
                }


                
            }
            $this->load->view('monitor/Head_beat', array("head_beat_blocks" => $block));
        })->wait();


    }

    private function gen_head_beat_urls() {
        // get all company
        $result = array();
        $data = $this->Company_model->get_all(array("status"=>"1"));
        if (empty($data)) {
            return $result;
        }
        foreach ($data as $key => $val) {
            $result[] = array(
                "cid" => $val['id'],
                "companyname" => $val['companyname'],
                "url" => $val['joburl'],
                "skey" => $val['secure_key']
            );
        }

        return $result;
    }

    private function database_access() {
        $database_expire = $this->predis->get('database_expire');
        if (empty($database_expire)) {
            $r1 = rand(1,10);
            $r2 = rand(1,10);
            $r3 = rand(1,10);

            $answer = $r1 + $r2 + $r3 + 80;
            $this->session->set_userdata('database_answer', $answer);

            $view_data['question'] = $r1.' + '.$r2.' + '.$r3;
            echo $this->load->view('monitor/Database_question', $view_data, true);
            exit;
        }
    }

    public function database() {
        $this->database_access();
        $this->search_items = array(
            "search_companyname" => $this->input->get("companyname", true),
            "search_tablename" => $this->input->get("tablename", true)
        );
        $this->session->set_userdata("monitor_database_search", $this->search_items);

        $view_data['monitor_database_search'] = $this->search_items;    

        if (!empty($this->search_items['search_companyname'])) {
            $this->Company_model->db_read->like("companyname", $this->search_items['search_companyname']);
        }

        $data = $this->Company_model->get_all(array("status"=>"1"));
        $tabs = '';
        $contents = '';
        if (!empty($data)) {
            foreach ($data as $key => $val) {
                if ($key == 0) {
                    $tabs .= '<li class="active"><a href="#tabcontent" data-toggle="tab" cid="'.$val['id'].'" class="companytab">'.$val['companyname'].'</a></li>';
                    $contents .= '<div class="tab-pane active" id="tabcontent">'.$this->database_struct($val['id']).'</div>';
                } else {
                    $tabs .= '<li><a href="#tabcontent" data-toggle="tab" cid="'.$val['id'].'" class="companytab">'.$val['companyname'].'</a></li>';
                }
            }            
        }

        $view_data['tabs'] = $tabs;
        $view_data['contents'] = $contents;
        $this->load->view('monitor/Database', $view_data);
    }

    private function database_struct($cid=0) {
        $this->database_url = "/oracle/dbstructure";
        $this->database_token = array(
            "iss" => "Database Structure",
            "aud" => "syoracle",
            "iat" => time()
        );
        $error = array(
            "status" => "error"
        );

        if (empty($cid)) {
            $error['msg'] = "Unauthorized Usage!";
            $this->response($error, 200);
        }    
        $client = new Client();

        $data = $this->Company_model->get_one(array("id"=>$cid));

        if (empty($data)) {
            $error['msg'] = "Unauthorized Usage!";
            $this->response($error, 200);            
        }


        $token = JWT::encode($this->database_token, $data['secure_key']);
        try {
            $response = $client->get($data['joburl'].$this->database_url, ["headers"=>["Authorization"=>$token]]);
        } catch (Exception $e) {
            echo $e;exit;
        }
        

        $res = json_decode($response->getBody(), true);  
        if (empty($res)) {
            return '<span style="color:red">Wrong URL</span>';
        }
        $result = '';
        $result .= '<div class="accordion" id="accordion_database" role="tablist" aria-multiselectable="true">';
        foreach ($res as $key => $val) {
            if (!empty($this->search_items['search_tablename'])) {
                if (false === strpos($val['tableName'], $this->search_items['search_tablename'])) {
                    continue;
                }
            }
            $result .= '<div class="panel">
                    <a class="panel-heading" role="tab" id="'.$val['tableName'].'_head" data-toggle="collapse" data-parent="#accordion_database" href="#'.$val['tableName'].'_collap" aria-expanded="true" aria-controls="'.$val['tableName'].'_collap">
                        <h4 class="panel-title">'.$val['tableName'].'</h4>
                    </a>
                    <div id="'.$val['tableName'].'_collap" class="panel-collapse collapse" role="tabpanel" aria-labelledby="'.$val['tableName'].'_head">
                        <div class="panel-body">
                            <table class="table table-striped responsive-utilities jambo_table bulk_action">
                                <thead>
                                    <tr>
                                        <th>Field</th>
                                        <th>Type</th>
                                        <th>Collation</th>
                                        <th>Null</th>
                                        <th>Key</th>
                                        <th>Default</th>
                                        <th>Extra</th>
                                        <th>Comment</th>
                                    </tr>
                                </thead>
                                <tbody>
                ';
            $tr = '';
            foreach ($val['tableFields'] as $k2 => $v2) {
                $tr .= '<tr><td>'.$v2['Field'].'</td><td>'.$v2['Type'].'</td><td>'.$v2['Collation'].'</td><td>'.$v2['Null'].'</td><td>'.$v2['Key'].'</td><td>'.$v2['Default'].'</td><td>'.$v2['Extra'].'</td><td>'.$v2['Comment'].'</td></tr>';
            }

            $result .= $tr.'</tbody></table></div></div></div>';
        }
        $result .= '</div>';

        return $result;
    }

    public function redis() {

        $this->search_items = array(
            "search_companyname" => $this->input->get("companyname", true),
            "search_rediskey" => $this->input->get("rediskey", true)
        );
        $this->session->set_userdata("monitor_redis_search", $this->search_items);

        $view_data['monitor_redis_search'] = $this->search_items;        

        if (!empty($this->search_items['search_companyname'])) {
            $this->Company_model->db_read->like("companyname", $this->search_items['search_companyname']);
        }

        $data = $this->Company_model->get_all(array("status"=>"1"));
        $tabs = '';
        $contents = '';
        if (!empty($data)) {
            foreach ($data as $key => $val) {
                if ($key == 0) {
                    $tabs .= '<li class="active"><a href="#tabcontent" data-toggle="tab" cid="'.$val['id'].'" class="companytab">'.$val['companyname'].'</a></li>';
                    $contents .= '<div class="tab-pane active" id="tabcontent">'.$this->redis_cache($val['id']).'</div>';
                } else {
                    $tabs .= '<li><a href="#tabcontent" data-toggle="tab" cid="'.$val['id'].'" class="companytab">'.$val['companyname'].'</a></li>';
                }
            }            
        }

        $view_data['tabs'] = $tabs;
        $view_data['contents'] = $contents;
        $this->load->view('monitor/Redis', $view_data);
    }

    private function redis_cache($cid=0) {
        $this->redis_webcache = "/oracle/cache/webcache";
        $this->redis_session = "/oracle/cache/session";
        $this->redis_agent = "/oracle/cache/agent";
        $this->redis_job = "/oracle/cache/job";
        $this->database_token = array(
            "iss" => "Redis",
            "aud" => "syoracle",
            "iat" => time()
        );
        $error = array(
            "status" => "error"
        );

        if (empty($cid)) {
            $error['msg'] = "Unauthorized Usage!";
            $this->response($error, 200);
        }    
        $client = new Client();

        $data = $this->Company_model->get_one(array("id"=>$cid));

        if (empty($data)) {
            $error['msg'] = "Unauthorized Usage!";
            $this->response($error, 200);            
        }

        $token = JWT::encode($this->database_token, $data['secure_key']);
        try {
            $res_webcache = json_decode($client->get($data['joburl'].$this->redis_webcache, ["headers"=>["Authorization"=>$token]])->getBody(), true);
            $res_session = json_decode($client->get($data['joburl'].$this->redis_session, ["headers"=>["Authorization"=>$token]])->getBody(), true);
            $res_agent = json_decode($client->get($data['joburl'].$this->redis_agent, ["headers"=>["Authorization"=>$token]])->getBody(), true);
            $res_job = json_decode($client->get($data['joburl'].$this->redis_job, ["headers"=>["Authorization"=>$token]])->getBody(), true);
        } catch (Exception $e) {
            return '<span style="color:red">'.$e->getMessage().'</span>';
        }

        if (empty($res_webcache)) {
            return '<span style="color:red">no redis show!</span>';
        }


        return '<div class="accordion" id="accordion" role="tablist" aria-multiselectable="false">'.$this->render_panel($res_webcache,'webcache','Web Cache').$this->render_panel($res_session, 'session','Session').$this->render_panel($res_agent,'agent','Agent').$this->render_panel($res_job,'job','Job').'</div>';
    }

    private function render_panel($res=array(),$name='', $title='', $in = "") {
        $get = $this->input->get();
        $result = '';
        $result .= '<div class="panel">
                <a class="panel-heading" role="tab" id="'.$name.'_head" data-toggle="collapse" data-parent="#accordion" href="#'.$name.'_collap" aria-expanded="true" aria-controls="'.$name.'_collap">
                    <h4 class="panel-title">'.$title.'</h4>
                </a>
                <div id="'.$name.'_collap" class="panel-collapse collapse '.$in.'" role="tabpanel" aria-labelledby="'.$name.'_head">
                    <div class="panel-body">
                    ';        
        $result .= '<div class="accordion" id="'.$name.'_accordion" aria-multiselectable="true">';
        $rediskeys = "";
        foreach ($res as $key => $val) {
            if (!empty($this->search_items['search_rediskey'])) {
                if (false === strpos($key, $this->search_items['search_rediskey'])) {
                    continue;
                }
            }
            $keyname = md5($name.$key);

            $redis_content = htmlspecialchars(json_encode($val, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            $rediskeys .= '<div class="panel inner_accordion">
                    <a style="border:1px solid #ddd" class="panel-heading inner-head" role="tab" id="head_'.$keyname.'" data-toggle="collapse" data-parent="#'.$name.'_accordion" href="#collap_'.$keyname.'" aria-expanded="true" aria-controls="collap_'.$keyname.'">
                        <h4 class="panel-title"><strong>'.$key.'</strong></h4>
                    </a>
                    <div id="collap_'.$keyname.'" class="panel-collapse collapse" role="tabpanel" aria-labelledby="head_'.$keyname.'">
                    <div class="panel-body fixcontent" style="border:1px solid #ddd; border-top:0">
                            <button class="btn btn-primary redis-copy-to-btn" data-clipboard-action="copy" data-clipboard-target="#copy_'.$keyname.'">
                                    Copy to clipboard
                            </button>
                            <textarea rows="'.(substr_count($redis_content, "\n")+8).'" class="form-control" id="copy_'.$keyname.'" style="width:100%;">'.$redis_content.'</textarea>
                        </div>
                    </div></div>';   
        }
        if ($rediskeys == "") {
            return "";
        }
        $result .= $rediskeys.'</div>';
        $result .= "</div></div></div>";
        return $result;
    }


}
