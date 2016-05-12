<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Monitor extends MY_REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['company_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['company_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['company_delete']['limit'] = 50; // 50 requests per hour per user/key

        $this->load->model('Company_model');
    }


    public function redis_get($cid=0) {
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
            $this->response($error, 400);
        }    
        
        if (!empty($this->session->userdata("monitor_redis_search"))) {
            $this->search_items = $this->session->userdata("monitor_redis_search");
        } else {
            $this->search_items = array();
        }

        $client = new Client();

        $data = $this->Company_model->get_one(array("id"=>$cid));

        if (empty($data)) {
            $error['msg'] = "Unauthorized Usage!";
            $this->response($error, 400);            
        }

        $token = JWT::encode($this->database_token, $data['secure_key']);

        $res_webcache = json_decode($client->get($data['joburl'].$this->redis_webcache, ["headers"=>["Authorization"=>$token]])->getBody(), true);
        $res_session = json_decode($client->get($data['joburl'].$this->redis_session, ["headers"=>["Authorization"=>$token]])->getBody(), true);
        $res_agent = json_decode($client->get($data['joburl'].$this->redis_agent, ["headers"=>["Authorization"=>$token]])->getBody(), true);
        $res_job = json_decode($client->get($data['joburl'].$this->redis_job, ["headers"=>["Authorization"=>$token]])->getBody(), true);
        if (empty($res_webcache)) {
            $error['msg'] = "no redis show!";
            $this->response($error, 400);  
        }
        ob_start();
        echo '<div class="accordion" id="accordion" role="tablist" aria-multiselectable="false">'.$this->render_panel($res_webcache,'webcache','Web Cache', 'in').$this->render_panel($res_session, 'session','Session').$this->render_panel($res_agent,'agent','Agent').$this->render_panel($res_job,'job','Job').'</div>';        
        $output = ob_get_contents();
        ob_end_clean();
        echo json_encode([
            "status" => "success",
            "html" => $output
        ]);

    }

    private function render_panel($res=array(),$name='', $title='', $in = "") {

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
            $keyname = md5($key);
            $rediskeys .= '<div class="panel inner_accordion">
                    <a style="border:1px solid #ddd" class="panel-heading inner-head" role="tab" id="head_'.$keyname.'" data-toggle="collapse" data-parent="#'.$name.'_accordion" href="#collap_'.$keyname.'" aria-expanded="true" aria-controls="collap_'.$keyname.'">
                        <h4 class="panel-title"><strong>'.$key.'</strong></h4>
                    </a>
                    <div id="collap_'.$keyname.'" class="panel-collapse collapse" role="tabpanel" aria-labelledby="head_'.$keyname.'">
                    <div class="panel-body fixcontent" style="border:1px solid #ddd; border-top:0">
                            <button class="btn btn-primary redis-copy-to-btn" data-clipboard-action="copy" data-clipboard-target="#copy_'.$keyname.'">
                                    Cut to clipboard
                            </button>
                            <textarea class="form-control" id="copy_'.$keyname.'" style="width:100%;">'.htmlspecialchars(json_encode($val, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)).'</textarea>
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
    public function database_struct_get($cid=0) {
        $this->database_url = "/oracle/dbstructure";
        $this->database_token = array(
            "iss" => "Database",
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

        if (!empty($this->session->userdata("monitor_database_search"))) {
            $this->search_items = $this->session->userdata("monitor_database_search");
        } else {
            $this->search_items = array();
        }

        $client = new Client();

        $data = $this->Company_model->get_one(array("id"=>$cid));

        if (empty($data)) {
            $error['msg'] = "Unauthorized Usage!";
            $this->response($error, 200);            
        }


        $token = JWT::encode($this->database_token, $data['secure_key']);

        $response = $client->get($data['joburl'].$this->database_url, ["headers"=>["Authorization"=>$token]]);

        $result = json_decode($response->getBody(), true);

        if (empty($result)) {
            $error['msg'] = "Wrong URL";
            $this->response($error, 400);  
        }  

        ob_start();
        echo '<div class="accordion" id="accordion" role="tablist" aria-multiselectable="false">';
        foreach ($result as $key => $val) {
            if (!empty($this->search_items['search_tablename'])) {
                if (false === strpos($val['tableName'], $this->search_items['search_tablename'])) {
                    continue;
                }
            }
            echo '<div class="panel">
                    <a class="panel-heading" role="tab" id="'.$val['tableName'].'_head" data-toggle="collapse" data-parent="#accordion" href="#'.$val['tableName'].'_collap" aria-expanded="true" aria-controls="'.$val['tableName'].'_collap">
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

            echo $tr.'</tbody></table></div></div></div>';
        }
        echo '</div>';
        $output = ob_get_contents();
        ob_end_clean();
        echo json_encode([
            "status" => "success",
            "html" => $output
        ]);
    }

    public function head_beat_refresh_get($cid=0)
    {
        // $this->some_model->update_user( ... );
        $this->head_beat_url = "/oracle/heartbeat";
        $this->head_beat_token = array(
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


        $token = JWT::encode($this->head_beat_token, $data['secure_key']);

        $response = $client->get($data['joburl'].$this->head_beat_url, ["headers"=>["Authorization"=>$token]]);

        $result = json_decode($response->getBody(), true);

        $html =  '<table class="table table-striped">
                    <tbody>
                        <tr>
                            <td style="text-align: right;width:50%">Clear Gateway : </td>
                            <td style="width:50%">'.$result['clear_gateway_accumulate_amount']['start'].'</td>
                        </tr>
                        <tr>
                            <td style="text-align: right;width:50%">Overdue Deposit : </td>
                            <td style="width:50%">'.$result['handle_overdue_deposit']['start'].'</td>
                        </tr>
                        <tr>
                            <td style="text-align: right;width:50%">Member Level Upgrade : </td>
                            <td style="width:50%">'.$result['monthly_member_upgrade']['start'].'</td>
                        </tr>
                        <tr>
                            <td style="text-align: right;width:50%">Member Level Pre Upgrade : </td>
                            <td style="width:50%">'.$result['monthly_member_upgrade_pre']['start'].'</td>
                        </tr>
                        <tr>
                            <td style="text-align: right;width:50%">Reject Friend Referral : </td>
                            <td style="width:50%">'.$result['event_reject_friend_referral']['start'].'</td>
                        </tr>
                        <tr>
                            <td style="text-align: right;width:50%">Agent Comm Pre : </td>
                            <td style="width:50%">'.$result['monthly_agent_commission_send_pre']['start'].'</td>
                        </tr>
                        <tr>
                            <td style="text-align: right;width:50%">Agent Comm Sent : </td>
                            <td style="width:50%">'.$result['monthly_agent_commission_send']['start'].'</td>
                        </tr>
                        <tr>
                            <td style="text-align: right;width:50%">Monthly Agent Suspend : </td>
                            <td style="width:50%">'.$result['monthly_agent_suspend']['start'].'</td>
                        </tr>
                        <tr>
                            <td style="text-align: right;width:50%">Game Balance : </td>
                            <td style="width:50%">'.$result['game_balance']['start'].'</td>
                        </tr>
                    </tbody>
                </table>';
        $this->response(["status"=>"success", "html"=>$html], 200);

    }


}
