<?php
defined('BASEPATH') OR exit('No direct script access allowed');


use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;

class Monitor extends MY_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *      http://example.com/index.php/welcome
     *  - or -
     *      http://example.com/index.php/welcome/index
     *  - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */

    public $page_type;
    
    public function __construct() {
        parent::__construct();

        $this->page_type = 'ajax';
        $this->load->model('Company_model');


    }

    public function index() {
         $this->load->view('pagenofound');
    }

    public function head_beat() {
        $this->head_beat_url = "/oracle/heartbeat";
        $this->head_beat_status = "";
        $this->head_beat_token = array(
            "iss" => "Heartbeat",
            "aud" => "syoracle",
            "iat" => time()
        );
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

                if ($key % 2 == 0) {
                    $block .= '<div class="clearfix"></div>';
                }
                $block_data = [];
                if ($response['state'] != 'fulfilled') {
                    $error = $response['reason']->getHandlerContext();
                    // output inactive
                    $block_data['status'] = 'inactive';
                    $block_data['companyname'] = $this->head_beat_urls[$key]['companyname'];
                    $block_data['cid'] = $this->head_beat_urls[$key]['cid'];
                    $block_data['head_beat_status'] = '<i class="fa fa-minus-square" style="color:red"></i> Inactive';
                    $block_data['error_msg'] = $error['error'];
                    $block .= $this->load->view("monitor/head_beat_block", $block_data, true);
            
                    continue;
                }
                
                ob_start();

                echo $response['value']->getBody()->getContents();
                $output = ob_get_contents();
                ob_end_clean();
                $result = json_decode($output);

                $block_data['status'] = 'active';
                $block_data['companyname'] = $this->head_beat_urls[$key]['companyname'];
                $block_data['cid'] = $this->head_beat_urls[$key]['cid'];
                $block_data['head_beat_status'] = '<i class="fa fa-check-square" style="color:green"></i> Active';
                $block_data['clear_gateway_accumulate_amount'] = $result->clear_gateway_accumulate_amount->start;
                $block_data['handle_overdue_deposit'] = $result->handle_overdue_deposit->start;
                $block_data['monthly_member_upgrade'] = $result->monthly_member_upgrade->start;
                $block_data['monthly_member_upgrade_pre'] = $result->monthly_member_upgrade_pre->start;
                $block_data['event_reject_friend_referral'] = $result->event_reject_friend_referral->start;
                $block_data['monthly_agent_commission_send_pre'] = $result->monthly_agent_commission_send_pre->start;
                $block_data['monthly_agent_commission_send'] = $result->monthly_agent_commission_send->start;
                $block_data['monthly_agent_suspend'] = $result->monthly_agent_suspend->start;
                $block_data['game_balance'] = $result->game_balance->start;
                // $block_data = array_merge($result, $block_data);

                $block .= $this->load->view("monitor/head_beat_block", $block_data, true);
                
            }
            $this->load->view('monitor/head_beat', array("head_beat_blocks" => $block));
        })->wait();


    }

    private function gen_head_beat_urls() {
        // get all company
        $result = array();
        $data = $this->Company_model->get_all();
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

    public function database() {

        $data = $this->Company_model->get_all();
        $tabs = '';
        $contents = '';
        foreach ($data as $key => $val) {
            if ($key == 0) {
                $tabs .= '<li class="active"><a href="#tabcontent" data-toggle="tab" cid="'.$val['id'].'" class="companytab">'.$val['companyname'].'</a></li>';
                $contents .= '<div class="tab-pane active" id="tabcontent">'.$this->database_struct($val['id']).'</div>';
            } else {
                $tabs .= '<li><a href="#tabcontent" data-toggle="tab" cid="'.$val['id'].'" class="companytab">'.$val['companyname'].'</a></li>';
            }
        }


        $view_data['tabs'] = $tabs;
        $view_data['contents'] = $contents;
        $this->load->view('monitor/database', $view_data);
    }

    private function database_struct($cid=0) {
        $this->database_url = "/oracle/dbstructure";
        $this->database_token = array(
            "iss" => "Heartbeat",
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

        $response = $client->get($data['joburl'].$this->database_url, ["headers"=>["Authorization"=>$token]]);

        $res = json_decode($response->getBody(), true);  
        $result = '';
        $result .= '<div class="accordion" role="tablist" aria-multiselectable="true">';
        foreach ($res as $key => $val) {
            $in = '';
            if ($key == 0) {
                $in = ' in';
            }
            $result .= '<div class="panel">
                    <a class="panel-heading" role="tab" id="'.$val['tableName'].'_head" data-toggle="collapse" data-parent="#accordion" href="#'.$val['tableName'].'_collap" aria-expanded="true" aria-controls="'.$val['tableName'].'_collap">
                        <h4 class="panel-title">'.$val['tableName'].'</h4>
                    </a>
                    <div id="'.$val['tableName'].'_collap" class="panel-collapse collapse'.$in.'" role="tabpanel" aria-labelledby="'.$val['tableName'].'_head">
                        <div class="panel-body">
                            <table class="table table-bordered">
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


}
