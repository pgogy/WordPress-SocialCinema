<?php

class social_cinema_archive_library{

	function get_same_search(){
	
		require_once("social_cinema_cache.php");		
		$cache = new social_cinema_cache();
		
		if($cache->cached("archive-" . $_POST['term'])){
			$data = $cache->get_cache("archive-" . $_POST['term']);
		}else{
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_SSL_VERIFYPEER => 0,
				CURLOPT_URL => "https://archive.org/advancedsearch.php?q=title%3A%22" . $_POST['term'] . "%22+format%3A%22MPEG4%22&fl%5B%5D=identifier&fl%5B%5D=mediatype&sort%5B%5D=&sort%5B%5D=&sort%5B%5D=&rows=50&page=1&output=json"
			));
			$resp = curl_exec($curl);
			if(curl_error($curl)==""){
				$data = $resp;
				$data = json_decode($resp);
				$cache->set_cache("archive-" . $_POST['term'], $data);
			}else{
				echo curl_error($curl) . "<br />";
			}
			curl_close($curl);
		}

		$video_list = array();
		
		if(isset($data)){

			foreach($data->response->docs as $video){
			
				if($cache->cached("archiveidentifier-" . $video->identifier)){
					$item_data = $cache->get_cache("archiveidentifier-" . $video->identifier);
				}else{
					$curl = curl_init();
					curl_setopt_array($curl, array(
						CURLOPT_RETURNTRANSFER => 1,
						CURLOPT_SSL_VERIFYPEER => 0,
						CURLOPT_URL => "http://archive.org/metadata/" . $video->identifier
					));
					$item_data = curl_exec($curl);
					$item_data = json_decode($item_data);
					$cache->set_cache("archiveidentifier-" . $video->identifier, $item_data);
				}
			
				if(isset($item_data->files)){

					foreach($item_data->files as $file){
						if($file->format=="Thumbnail"){
							if(isset($item_data->d1)){
								$path = $item_data->d1;
							}else{
								$path = $item_data->d2;
							}
							$thumbnail = $path . $item_data->dir . "/" . $file->name; 
						}else if(strpos($file->format,"MPEG4")!==FALSE){
							$file_name = $path . $item_data->dir . "/" . $file->name; 
						}
						
						if(isset($file_name) && isset($thumbnail)){
							$video_list[] = array("archive", "https://" . $file_name, "https://" . $thumbnail);
						}
					}
					
				}

			}
			
		}
		
		return $video_list;
	
	}
	
	function search($atts){
	
		require_once("social_cinema_cache.php");		
		$cache = new social_cinema_cache();
		
		if($cache->cached("archive-" . $atts['term'])){
			$data = $cache->get_cache("archive-" . $atts['term']);
		}else{
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_SSL_VERIFYPEER => 0,
				CURLOPT_URL => "https://archive.org/advancedsearch.php?q=title%3A%22" . $atts['term'] . "%22+format%3A%22MPEG4%22&fl%5B%5D=identifier&fl%5B%5D=mediatype&sort%5B%5D=&sort%5B%5D=&sort%5B%5D=&rows=50&page=1&output=json"
			));
			$resp = curl_exec($curl);
			if(curl_error($curl)==""){
				$data = $resp;
				$data = json_decode($resp);
				$cache->set_cache("archive-" . $atts['term'], $data);
			}else{
				echo curl_error($curl) . "<br />";
			}
			curl_close($curl);
		}

		$video_list = array();
		
		if(isset($data)){

			foreach($data->response->docs as $video){
			
				if($cache->cached("archiveidentifier-" . $video->identifier)){
					$item_data = $cache->get_cache("archiveidentifier-" . $video->identifier);
				}else{
					$curl = curl_init();
					curl_setopt_array($curl, array(
						CURLOPT_RETURNTRANSFER => 1,
						CURLOPT_SSL_VERIFYPEER => 0,
						CURLOPT_URL => "http://archive.org/metadata/" . $video->identifier
					));
					$item_data = curl_exec($curl);
					$item_data = json_decode($item_data);
					$cache->set_cache("archiveidentifier-" . $video->identifier, $item_data);
				}
			
				if(isset($item_data->files)){

					foreach($item_data->files as $file){
						if($file->format=="Thumbnail"){
							if(isset($item_data->d1)){
								$path = $item_data->d1;
							}else{
								$path = $item_data->d2;
							}
							$thumbnail = $path . $item_data->dir . "/" . $file->name; 
						}else if(strpos($file->format,"MPEG4")!==FALSE){
							$file_name = $path . $item_data->dir . "/" . $file->name; 
						}
						
						if(isset($file_name) && isset($thumbnail)){
							$video_list[] = array("archive", "https://" . $file_name, "https://" . $thumbnail);
						}
					}
					
				}

			}
			
		}
		
		return $video_list;
		
	}
	
	function get_single_video($atts){
	
		require_once("social_cinema_cache.php");		
		$cache = new social_cinema_cache();
	
		if(strpos($atts['video'],"http")!==FALSE){
			$parts = explode("/", $atts['video']);
			$id = array_pop($parts);
		}else{
			$id = $atts['video'];
		}
		
		if($cache->cached("archiveidentifier-" . $id)){
			$item_data = $cache->get_cache("archiveidentifier-" . $id);
		}else{
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_RETURNTRANSFER => 1,				
				CURLOPT_SSL_VERIFYPEER => 0,
				CURLOPT_URL => "http://archive.org/metadata/" . $id
			));
			$resp = curl_exec($curl);
			if(curl_error($curl)==""){
				$item_data = $resp;
				$item_data = json_decode($resp);
				$cache->set_cache("archiveidentifier-" . $id, $item_data);
			}
			curl_close($curl);
		}
		
		$video_list = array();
		
		if(isset($item_data)){
		
			foreach($item_data->files as $file){
			
				if($file->format=="Thumbnail"){
					if(isset($item_data->d1)){
						$path = $item_data->d1;
					}else{
						$path = $item_data->d2;
					}
					$thumbnail = $path . "/" . $item_data->dir . "/" . $file->name; 
				}else if(strpos($file->format,"MPEG4")!==FALSE){
					$file_name = $path . "/" . $item_data->dir . "/" . $file->name; 
				}
				
				if(isset($file_name)){
					$video_list[] = array("archive", "https://" . $file_name, "https://" . $thumbnail);
					unset($file_name);
				}
			}
			
			return $video_list; 
		
		}else{
		
			return $video_list;
		
		}
		
	}

}