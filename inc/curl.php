<?php
class cURL {
	function get($url)
	{
		$process = curl_init($url);
		curl_setopt($process, CURLOPT_SSL_VERIFYHOST, false); // Ignore cert errors?
		curl_setopt($process, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($process, CURLOPT_CUSTOMREQUEST, "GET");     
		curl_setopt($process, CURLOPT_RETURNTRANSFER, true);            
		curl_setopt($process, CURLOPT_USERPWD, "username:password");      
		$return = curl_exec($process);
		curl_close($process);
		return $return;
	}
}
?>