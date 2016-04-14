<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use \Firebase\JWT\JWT;

class Testing extends CI_Controller {

    public function index()
    {
		$key = "example_key";
		$token = array(
		    "iss" => "http://example.org",
		    "aud" => "http://example.com",
		    "iat" => 1356999524,
		    "nbf" => 1357000000
		);

		$jwt = JWT::encode($token, $key);
		$decoded = JWT::decode($jwt, $key, array('HS256'));

		print_r($decoded);
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

    public function output_json2() {
        echo '{"records":"1000000","page":2,"total":50000,"rows":[{"OrderID":"1","CustomerID":"WILMK","OrderDate":"1996-07-04 00:00:00","Freight":"32.3800","ShipName":"Vins et alcools Chevalier"},{"OrderID":"2","CustomerID":"TRADH","OrderDate":"1996-07-05 00:00:00","Freight":"11.6100","ShipName":null},{"OrderID":"3","CustomerID":"HANAR","OrderDate":"1996-07-08 00:00:00","Freight":"65.8300","ShipName":"Hanari Carnes"},{"OrderID":"4","CustomerID":"VICTE","OrderDate":"1996-07-08 00:00:00","Freight":"41.3400","ShipName":"Victuailles en stock"},{"OrderID":"5","CustomerID":"SUPRD","OrderDate":"1996-07-09 00:00:00","Freight":"51.3000","ShipName":null},{"OrderID":"6","CustomerID":"HANAR","OrderDate":"1996-07-10 00:00:00","Freight":"58.1700","ShipName":"Hanari Carnes"},{"OrderID":"7","CustomerID":"CHOPS","OrderDate":"1996-07-11 00:00:00","Freight":"22.9800","ShipName":"Chop-suey Chinese"},{"OrderID":"8","CustomerID":"RICSU","OrderDate":"1996-07-12 00:00:00","Freight":"148.3300","ShipName":"Richter Supermarkt"},{"OrderID":"9","CustomerID":"WELLI","OrderDate":"1996-07-15 00:00:00","Freight":"13.9700","ShipName":"Wellington Importadora"},{"OrderID":"10","CustomerID":"HILAA","OrderDate":"1996-07-16 00:00:00","Freight":"81.9100","ShipName":null},{"OrderID":"11","CustomerID":"ERNSH","OrderDate":"1996-07-17 00:00:00","Freight":"140.5100","ShipName":"Ernst Handel"},{"OrderID":"12","CustomerID":"CENTC","OrderDate":"1996-07-18 00:00:00","Freight":"3.2500","ShipName":"Centro comercial Moctezuma"},{"OrderID":"13","CustomerID":"OLDWO","OrderDate":"1996-07-19 00:00:00","Freight":"55.0900","ShipName":null},{"OrderID":"14","CustomerID":"QUEDE","OrderDate":"1996-07-19 00:00:00","Freight":"3.0500","ShipName":null},{"OrderID":"15","CustomerID":"RATTC","OrderDate":"1996-07-22 00:00:00","Freight":"48.2900","ShipName":"Rattlesnake Canyon Grocery"},{"OrderID":"16","CustomerID":"ERNSH","OrderDate":"1996-07-23 00:00:00","Freight":"146.0600","ShipName":"Ernst Handel"},{"OrderID":"17","CustomerID":"FOLKO","OrderDate":"1996-07-24 00:00:00","Freight":"3.6700","ShipName":null},{"OrderID":"18","CustomerID":"BLONP","OrderDate":"1996-07-25 00:00:00","Freight":"55.2800","ShipName":null},{"OrderID":"19","CustomerID":"WARTH","OrderDate":"1996-07-26 00:00:00","Freight":"25.7300","ShipName":"Wartian Herkku"},{"OrderID":"20","CustomerID":"FRANK","OrderDate":"1996-07-29 00:00:00","Freight":"208.5800","ShipName":"Frankenversand"}]}';exit;
    }
}
