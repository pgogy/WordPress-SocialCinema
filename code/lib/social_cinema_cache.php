<?php

class social_cinema_cache{

	function cached($file_name){
	
		$cache_path = plugin_dir_path( __FILE__ );
		
		$cache_options = get_option('social-cinema-cache');
		
		if(strpos($file_name, "youtube") !== FALSE){
			if(isset($cache_options['youtube-cache-duration'])){
				$time = (time() - $cache_options['youtube-cache-duration']);
			}
		}
		
		if(strpos($file_name, "vimeo") !== FALSE){
			if(isset($cache_options['vimeo-cache-duration'])){
				$time = (time() - $cache_options['vimeo-cache-duration']);
			}
		}
		
		if(strpos($file_name, "archive") !== FALSE){
			if(isset($cache_options['archive-cache-duration'])){
				$time = (time() - $cache_options['archive-cache-duration']);
			}
		}
		
		if(!isset($time)){
			$time = 60;
		}
		
		if(file_exists($cache_path . "../../cache/" . urlencode(str_replace(".","--",$file_name)))){
			if(filemtime($cache_path . "../../cache/" . urlencode(str_replace(".","--",$file_name))) > $time){
				return true;
			}else{
				return false;
			}
		}
		
	}
	
	function get_cache($file_name){
		$cache_path = plugin_dir_path( __FILE__ );
		if(file_exists($cache_path . "../../cache/" . urlencode(str_replace(".","--",$file_name)))){
			return unserialize(file_get_contents($cache_path . "../../cache/" . urlencode(str_replace(".","--",$file_name))));
		}
	}
	
	function set_cache($file_name, $data){
		$cache_path = plugin_dir_path( __FILE__ );
		file_put_contents($cache_path . "../../cache/" . urlencode(str_replace(".","--",$file_name)), serialize($data));
	}

}