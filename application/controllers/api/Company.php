<?php

defined('BASEPATH') OR exit('No direct script access allowed');



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
class Company extends MY_REST_Controller {

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


    public function list_get()
    {
        // $this->some_model->update_user( ... );
        $get = $this->input->get();

        if (empty($get['page']) || empty($get['rows'])) {
            $this->bad_request();
        }

        $startRow = ($get['page'] - 1) * (empty($get['rows'])?20:$get['rows']);

        $data = $this->Company_model->get_company_list(array(), $startRow, $get['rows']);
        $result = [
            'records' => $data['records'] ,// total records
            'page' => $get['page'],
            'total' => round($data['records'] / $get['rows'], 0, PHP_ROUND_HALF_DOWN) + (($data['records'] % $get['rows'] > 0)?1:0),
            'rows' => empty($data['data'])?array():$data['data']
        ];

        $this->response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
    }


}
