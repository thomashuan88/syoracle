<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['AUTH'] = array(
    "SUPER_ADMIN" => array(
        'admin' => array('index', 'logout')
    ),
    "ADMIN" => array(
        'admin' => array('index', 'logout'),
        'home' => array('testing')
    )
);