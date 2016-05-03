<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use Firebase\JWT\JWT;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;


class Testing extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->token = array(
            "iss" => "Heartbeat",
            "aud" => "syoracle",
            "iat" => time()
        );

        $this->urls = array(
            array(
                "cid" => 1,
                "url" => "http://job.zhang/oracle/heartbeat",
                "skey" => "secret"
            ),
            array(
                "cid" => 2,
                "url" => "http://job.zhang/oracle/dbstructure/lastUpdate",
                "skey" => "secret"
            ),
            array(
                "cid" => 3,
                "url" => "http://jobQQ.zhang/oracle/cache/agent",
                "skey" => "secret"
            ),
            array(
                "cid" => 4,
                "url" => "http://job.zhang/oracle/cache/session",
                "skey" => "secret"
            )
        );    
    }
    public function index()
    {
		$key = "example_key";
		$token = array(
		    "iss" => "Heartbeat",
		    "aud" => "syoracle",
		    "iat" => time()
		);

		$jwt = JWT::encode($token, $key);
		$decoded = JWT::decode($jwt, $key, array('HS256'));

		print_r($decoded);
    }

    public function test_test() {
        // use Guzzle\Common\Exception\MultiTransferException;
        $client = new Client();
        try {
            $responses = $client->send(array(
                $client->get('http://www.google.com/'),
                $client->head('http://www.google.com/'),
                $client->get('https://www.github.com/')
            ));
        } catch (MultiTransferException $e) {

            echo "The following exceptions were encountered:\n";
            foreach ($e as $exception) {
                echo $exception->getMessage() . "\n";
            }

            echo "The following requests failed:\n";
            foreach ($e->getFailedRequests() as $request) {
                echo $request . "\n\n";
            }

            echo "The following requests succeeded:\n";
            foreach ($e->getSuccessfulRequests() as $request) {
                echo $request . "\n\n";
            }
        }    
    }

    public function test_curl3() {

        $client = new Client();
        $request = array();

        $options = [
            "complete" => [
                "fn" => function(CompleteEvent $event) {
                    echo $event->getResponse->getBody();
                },
                "priority" => 0,
                "once" => false
            ]
        ];

        foreach ($this->urls as $val) {
            $token = JWT::encode($this->token, $val['skey']);
            $request[] = new Request('GET', $val['url'], ["headers"=>["Authorization"=>$token]]);
        }

        $pool = new Pool($client, $request, $options);
        $promise = $pool->promise();
        
        echo '<pre>';
        echo $promise->getResponse->getBody();

    }

    public function test_curl4() {
        $client = new Client();
        $promises = [];

        try {
            foreach ($this->urls as $val) {
                $token = JWT::encode($this->token, $val['skey']);
                $promises[] = $client->requestAsync('GET', $val['url'], ["headers"=>["Authorization"=>$token]]);
            }

            GuzzleHttp\Promise\all($promises)->then(function (array $responses) {
                $result = [];
                foreach ($responses as $response) {
                    // ob_start();
                    // echo $response->getBody();
                    // $output = ob_get_contents();
                    // ob_end_clean();
                    // $result[] = json_decode($output);

                    echo '<pre>';
                    print_r($response);exit;
                     // Do something with the profile.
                }
                // echo '<pre>'; print_r($result);exit;
            })->wait();
        } catch (RequestException $e) {
            // echo $e->getRequest();
            echo '<pre>';
            print_r($e);
            if ($e->hasResponse()) {
                echo $e->getResponse();
            }
        }

    }
    public function test_curl5() {
        $client = new Client();
        $this->promises = [];

        
        foreach ($this->urls as $val) {
            $token = JWT::encode($this->token, $val['skey']);

            $this->promises[] = $client->requestAsync('GET', $val['url'], ["headers"=>["Authorization"=>$token]]);
              
        }

        GuzzleHttp\Promise\settle($this->promises)->then(function ($responses) {
            $result = [];
                // echo '<pre>';print_r($responses);exit;
            echo '<pre>';
            foreach ($responses as $key => $response) {
                if ($response['state'] != 'fulfilled') {
                    // echo $key.' is bad'."<br>";
                    // print_r($response);
                    print_r($response['reason']->getHandlerContext());
                    continue;
                }
                // echo '<pre>';print_r($response);exit;
                // continue;
                // ob_start();

                echo $response['value']->getBody()->getContents()."<br>";
                // $output = ob_get_contents();
                // ob_end_clean();
                // $result[] = json_decode($output);

                // echo '<pre>';
                // print_r($response);exit;
                 // Do something with the profile.
            }
            // echo '<pre>'; print_r($result);exit;
        })->wait();


    }

    public function test_curl() {


        $client = new Client();
        $promises = array();
        $request = array();

        foreach ($this->urls as $val) {
            $token = JWT::encode($this->token, $val['skey']);
            $request[] = new Request('GET', $val['url'], ["headers"=>["Authorization"=>$token]]);
        }

        $result = $client->sendAsync($request[0])->then(function($response) {
            echo $response.getBody()."\n";
        });
        $result->wait();

        exit;

        $requests = function($urls) {

        };

        $pool = new Pool($client, $requests($urls), [
            'concurrency' => 5,
            'fulfilled' => function ($response, $index) {
                // this is delivered each successful response
                echo $response->getBody();
            },
            'rejected' => function ($reason, $index) {
                // this is delivered each failed request
            },
        ]);
        $promise = $pool->promise();
        $promise->wait();

        exit;
        try {
            $requests = array();
            foreach ($urls as $key => $val) {
                $token = JWT::encode($token, $val['skey']);
                $requests[] = $client->getAsync($val['url'], array('headers'=> array("Authorization"=>$token)));
            }   
            // echo $requests[0]->getBody();
            // echo '<pre>';
            // print_r($requests);exit;         
            $results = Promise\unwrap($requests);
            // $results = Promise\settle($requests)->wait();
            echo '<pre>';

            $res = $client->sent($results);
            print_r($res);
            // print_r($results[0]->getBody());exit;             
            
        } catch (MultiTransferException $e) {

            echo "The following exceptions were encountered:\n";
            foreach ($e as $exception) {
                echo $exception->getMessage() . "\n";
            }

            echo "The following requests failed:\n";
            foreach ($e->getFailedRequests() as $request) {
                echo $request . "\n\n";
            }

            echo "The following requests succeeded:\n";
            foreach ($e->getSuccessfulRequests() as $request) {
                echo $request . "\n\n";
            }
        }
    }

    public function test_curl2() {

        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'http://job.zhang/oracle/heartbeat',
            CURLOPT_HTTPHEADER => array(
                "Authorization: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJIZWFydGJlYXQiLCJhdWQiOiJzeW9yYWNsZSIsImlhdCI6MTQ2MTgzMTQyMn0.QYa8InjuCrqjeNqPSYkDMBLkVDwGoRvlvXAejBaR41s"
            )
        ));
        // Send the request & save response to $resp
        $resp = curl_exec($curl);
        // Close request to clear up some resources
        echo $resp;
        curl_close($curl);
    }
    
    public function test() {
        $this->predis->hashDel('login_ipaddress_192.168.10.1', "count");
        $this->predis->hashDel('login_ipaddress_192.168.10.1', "username");

        echo base_url();exit;
        $this->load->model('admin_model');

        // echo $this->router->method;

        $this->test2('admin_model', 'insert_admin');

        print_r($this->config->item('hash_key'));
    }

    public function test2($model, $method) {

        print_r($this->$model->$method()); 
    }

    public function testsession() {

        echo FCPATH; exit;
        $this->load->library('session');

        $this->session->set_userdata(array("asasfd"=>"adsads"));
        print_r($_SESSION);
    }

    public function output_json() {
        echo '{"records":"0","page":0,"total":0,"rows":[]}';exit;
    }


}
