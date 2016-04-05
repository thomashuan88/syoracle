<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Config for the CodeIgniter Redis library
 *
 * @see ../libraries/Redis.php
 */
// Default connection group
$config['redis_default']['host'] = '10.105.1.18';  // IP address or host
$config['redis_default']['port'] = '6379';   // Default Redis port is 6379
$config['redis_default']['password'] = '';   // Can be left empty when the server does not require AUTH
$config['redis_default']['prefix'] = 'sy_';   // Key prefix
$config['redis_default']['suffix'] = '_bo';   // Key suffix
$config['redis_default']['db'] = '0';    // Selected database

$config['redis_web']['host'] = '10.105.1.18';  // IP address or host
$config['redis_web']['port'] = '6379';   // Default Redis port is 6379
$config['redis_web']['password'] = '';   // Can be left empty when the server does not require AUTH
$config['redis_web']['prefix'] = 'sy_';   // Key prefix
$config['redis_web']['suffix'] = '_fe';   // Key suffix
$config['redis_web']['db'] = '0';    // Selected database

$config['redis_session']['host'] = '10.105.1.18';  // IP address or host
$config['redis_session']['port'] = '6379';   // Default Redis port is 6379
$config['redis_session']['password'] = '';   // Can be left empty when the server does not require AUTH
$config['redis_session']['prefix'] = 'sy_';   // Key prefix
$config['redis_session']['suffix'] = '_fe';   // Key suffix
$config['redis_session']['db'] = '1';    // Selected database

$config['redis_slave']['host'] = '';
$config['redis_slave']['port'] = '6379';
$config['redis_slave']['password'] = '';
$config['redis_slave']['prefix'] = 'sy_';
$config['redis_slave']['suffix'] = '_bo';   // Key suffix
$config['redis_slave']['db'] = '0';    // Selected database