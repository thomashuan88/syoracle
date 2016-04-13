<?php

class Predis {
	var $client;
	var $_ci;
    var $prefix;
	var $suffix;

	public function __construct($params = array())
    {
        require_once dirname(__FILE__) . '/predis/autoload.php';

    	$this->_ci = get_instance();
        $this->_ci->load->config('redis');
        $config = array();

        $connection_group = isset($params['connection_group']) ?
        						$params['connection_group'] : 'default';

        if (is_array($this->_ci->config->item('redis_'.$connection_group))) {
        	$config = $this->_ci->config->item('redis_'.$connection_group);
        	$this->connect($config['host'], $config['port'], $config['password']);
            $this->prefix = $config['prefix'];
            $this->suffix = $config['suffix'];
            $this->select($config['db']);
        } else {
            $this->connect(
            	$this->_ci->config->item('redis_host'),
            	$this->_ci->config->item('redis_port'),
            	$this->_ci->config->item('redis_password'));
            $this->prefix = $this->_ci->config->item('redis_prefix');
			$this->suffix = $this->_ci->config->item('redis_suffix');
            $this->select($this->_ci->config->item('db'));
        }
    }

	public function connect($host = '127.0.0.1', $port = 6379, $password = '')
    {
    	$this->client = new Predis\Client(array(
	    		'scheme' => 'tcp',
			    'host'   => $host,
			    'port'   => $port,
			    'password'   => $password,
    		));
    }

    public function disconnect()
    {
        $this->client->quit();
    }

    public function set($key, $value, $expire = 0)
    {
    	$this->client->set($this->prefix.$key.$this->suffix, $value);
			if($expire > 0){
	    	$this->client->expire($this->prefix.$key.$this->suffix, $expire);
			}
    }

    public function get($key)
    {
			if($this->client->get($this->prefix.$key.$this->suffix) != 'false'){
				return $this->client->get($this->prefix.$key.$this->suffix);
			}
    }

    public function del($key,$suffix='')
    {
        if($suffix == ''){
            $suffix = $this->suffix;
        }elseif($suffix == 'all'){
            $suffix = '';
        }
        return $this->client->del($this->prefix.$key.$suffix);
    }

	public function delSearch($key_search)
    {
		$keys = $this->client->keys($this->prefix.$key_search."*");
		foreach ($keys as $key) {
			$this->client->del($key);
		}
		return true;
    }

    public function del_byusercode($usercode='') {
        $keys = $this->client->keys("*".$usercode."*");
        foreach ($keys as $key) {
            $this->client->del($key);
        }
        return true;  
    }

    public function showkey($key_search,$suffix='')
    {
        if($suffix == ''){
            $suffix = $this->suffix;
        }elseif($suffix == 'all'){
            $suffix = '';
        }
        return $this->client->keys($this->prefix.$key_search.$suffix);
    }

	public function flushAll()
    {
    	return $this->client->flushall();
    }

    public function expire($key, $expire)
    {
        $this->client->expire($this->prefix.$key.$this->suffix, $expire);
    }

    public function hashSet($hash_key, $field, $value)
    {
        $this->client->hset($this->prefix.$hash_key.$this->suffix, $field, $value);
    }

    public function hashGet($hash_key, $field)
    {
        return $this->client->hget($this->prefix.$hash_key.$this->suffix, $field);
    }

    public function hashGetAll($hash_key)
    {
        return $this->client->hgetall($this->prefix.$hash_key.$this->suffix);
    }

    public function hashDel($hash_key, $field)
    {
        return $this->client->hdel($this->prefix.$hash_key.$this->suffix, $field);
    }

    public function select($db = 0)
    {
        $this->client->select($db);
    }

    public function rename($old_key, $new_key)
    {
        $this->client->rename($this->prefix.$old_key.$this->suffix, $this->prefix.$new_key.$this->suffix);
    }

}
