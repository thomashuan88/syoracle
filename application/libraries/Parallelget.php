<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Parallelget {
	
	function __construct() {
		//library not part of framework, call get_instance() to get framework codes
		$this->CI =& get_instance();
    }
	
	function multi_curl($urls){
		// Create get requests for each URL
		$mh = curl_multi_init();
		foreach($urls as $i => $url){
			// $ch[$i] = curl_init($url['url']);
			// curl_setopt($ch[$i], CURLOPT_CONNECTTIMEOUT,2); 
			// curl_setopt($ch[$i], CURLOPT_TIMEOUT,20); 
			// curl_setopt($ch[$i], CURLOPT_RETURNTRANSFER,1);
			// curl_setopt($ch[$i], CURLOPT_HTTPHEADER, array(
			// 	"Authorization: ".$url['token']
			// ));
			$ch[$i] = curl_init();
	        curl_setopt_array($ch[$i], array(
	            CURLOPT_RETURNTRANSFER => 1,
	            CURLOPT_URL => $url['url'],
	            CURLOPT_HTTPHEADER => array(
	                "Authorization: ".$url['token']
	            )
	        ));
			curl_multi_add_handle($mh, $ch[$i]);
		}

		// Start performing the request
		do {
			$execReturnValue = curl_multi_exec($mh, $runningHandles);
		} while ($execReturnValue == CURLM_CALL_MULTI_PERFORM);
		
		// Loop and continue processing the request
		while ($runningHandles && $execReturnValue == CURLM_OK) {
			// Wait forever for network
			$numberReady = curl_multi_select($mh);
			if ($numberReady != -1) {
			// Pull in any new data, or at least handle timeouts
				do {
					$execReturnValue = curl_multi_exec($mh, $runningHandles);
				} while ($execReturnValue == CURLM_CALL_MULTI_PERFORM);
			}
		}

		// Check for any errors
		if ($execReturnValue != CURLM_OK) {
			log_message('error','Curl multi read error '.$execReturnValue);
		}

		// Extract the content
		foreach($urls as $i => $url){
			// Check for errors
			$curlError = curl_error($ch[$i]);
			if($curlError == "") {
				$res[$i] = curl_multi_getcontent($ch[$i]);
			} else {
				// $this->CI->load->model('company', '', TRUE);
				// $this->CI->company->update_failurecount($i);
				// $cache_data = $this->CI->cache->getallkeys();
				// foreach ($cache_data as $cache_id => $cache) {
				//    if ( strpos($cache,'com_listing') !== false ) {
				// 		$this->CI->cache->delete($cache);
				// 	}elseif( strpos($cache,'com_audittrail') !== false ){
				// 		$this->CI->cache->delete($cache);
				// 	}
				// }
				// $this->CI->cache->delete('company_curl_url');
				// $this->CI->cache->delete('count_matchalert_0');
				// log_message('error','Curl error on handle '.$url['url'].': '.$curlError);
			}
			// Remove and close the handle
			curl_multi_remove_handle($mh, $ch[$i]);
			curl_close($ch[$i]);
		}
		// Clean up the curl_multi handle
		curl_multi_close($mh);

		return $res;
	}
	
}
?>