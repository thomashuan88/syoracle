<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/
$hook['post_controller_constructor'][] = array(
    'class'    => 'Acl',
    'function' => 'auth',
    'filename' => 'Acl.php',
    'filepath' => 'hooks'
);

$hook['post_controller'][] = array(
    'class'    => 'Layout',
    'function' => 'render',
    'filename' => 'Layout.php',
    'filepath' => 'hooks'
);

$hook['post_controller_constructor'][] = array(
    'class'    => 'Log',
    'function' => 'create_log',
    'filename' => 'Log.php',
    'filepath' => 'hooks'
);
