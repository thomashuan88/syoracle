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
                            <td style="text-align: right;width:35%">Clear Gateway : </td>
                            <td style="width:65%">'.$result['clear_gateway_accumulate_amount']['start'].'</td>
                        </tr>
                        <tr>
                            <td style="text-align: right;width:35%">Overdue Deposit : </td>
                            <td style="width:65%">'.$result['handle_overdue_deposit']['start'].'</td>
                        </tr>
                        <tr>
                            <td style="text-align: right;width:35%">Member Level Upgrade : </td>
                            <td style="width:65%">'.$result['monthly_member_upgrade']['start'].'</td>
                        </tr>
                        <tr>
                            <td style="text-align: right;width:35%">Member Level Pre Upgrade : </td>
                            <td style="width:65%">'.$result['monthly_member_upgrade_pre']['start'].'</td>
                        </tr>
                        <tr>
                            <td style="text-align: right;width:35%">Reject Friend Referral : </td>
                            <td style="width:65%">'.$result['event_reject_friend_referral']['start'].'</td>
                        </tr>
                        <tr>
                            <td style="text-align: right;width:35%">Agent Comm Pre : </td>
                            <td style="width:65%">'.$result['monthly_agent_commission_send_pre']['start'].'</td>
                        </tr>
                        <tr>
                            <td style="text-align: right;width:35%">Agent Comm Sent : </td>
                            <td style="width:65%">'.$result['monthly_agent_commission_send']['start'].'</td>
                        </tr>
                        <tr>
                            <td style="text-align: right;width:35%">Monthly Agent Suspend : </td>
                            <td style="width:65%">'.$result['monthly_agent_suspend']['start'].'</td>
                        </tr>
                        <tr>
                            <td style="text-align: right;width:35%">Game Balance : </td>
                            <td style="width:65%">'.$result['game_balance']['start'].'</td>
                        </tr>
                    </tbody>
                </table>';
        $this->response(["status"=>"success", "html"=>$html], 200);

    }


}
