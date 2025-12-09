<?php

//get settings
if (!function_exists('get_settings')) {
	 function get_settings()
	 {
		 $ci =& get_instance();
		 $ci->load->model('Model_settings');
		 return $ci->Model_settings->get_settings();
	 }
}

if (!function_exists('add_https')) {
	function add_https($url)
	{
		if (!empty(trim($url))) {
			if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
				$url = "https://" . $url;
			}
			return $url;
		}
	}
}
?>
