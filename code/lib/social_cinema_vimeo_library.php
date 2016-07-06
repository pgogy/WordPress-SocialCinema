<?php

use Vimeo\Vimeo;

class social_cinema_vimeo_library{

	function setup(){
	
		$this->config = require(__DIR__ . '/init.php');

		$this->lib = new Vimeo($config['client_id'], $config['client_secret']);

		$this->lib->setToken($config['access_token']);
	
	}

	function search($atts){

		$this->setup();
		require_once("social_cinema_cache.php");		
		$cache = new social_cinema_cache();
		
		if($cache->cached("vimeo-" . $atts['term'])){
			$videos = $cache->get_cache("vimeo-" . $atts['term']);
		}else{
			$videos = $this->lib->request('/videos', array('query' => $atts['term'], 'per_page' => $atts['total']));
			$cache->set_cache("vimeo-" . $atts['term'], $videos);
		}

		$video_list = array();

		foreach($videos['body']['data'] as $video){
			$video_list[] = array("vimeo", str_replace("videos", "video", $video['uri']), $video['pictures']['sizes'][0]['link']);
		}
		
		return $video_list;
		
	}
	
	function get_same_search(){

		$this->setup();
		require_once("social_cinema_cache.php");		
		$cache = new social_cinema_cache();
		
		if($cache->cached("vimeo-" . $_POST['term'])){
			$videos = $cache->get_cache("vimeo-" . $_POST['term']);
		}else{
			$videos = $this->lib->request('/videos', array('query' => $_POST['term'], 'per_page' => $_POST['total']));
			$cache->set_cache("vimeo-" . $_POST['term'], $videos);
		}

		$video_list = array();

		foreach($videos['body']['data'] as $video){
			$video_list[] = array("vimeo", str_replace("videos", "video", $video['uri']), $video['pictures']['sizes'][0]['link']);
		}
		
		return $video_list;
		
	}
	
	function get_next_search(){

		$this->setup();

		$videos = $this->lib->request('/videos', array('query' => $_POST['term'], 'per_page' => $_POST['total'], 'page' => $_POST['page_number']));

		$video_list = array();

		foreach($videos['body']['data'] as $video){
			$video_list[] = array("vimeo", str_replace("videos", "video", $video['uri']), $video['pictures']['sizes'][0]['link']);
		}
		
		return $video_list;
		
	}
	
	function get_single_video($atts){

		$this->setup();
		
		if(strpos($atts['video'],"http")!==FALSE){
			$parts = explode("/", $atts['video']);
			$id = array_pop($parts);
		}else{
			$id = $atts['video'];
		}
		
		require_once("social_cinema_cache.php");		
		$cache = new social_cinema_cache();
		
		if($cache->cached("vimeoidentifier-" . $id)){
			$videos = $cache->get_cache("vimeoidentifier-" . $id);
		}else{
			$videos = $this->lib->request('/videos/'. $id);
			$cache->set_cache("vimeoidentifer-" . $id, $videos);
		}

		$videos = $this->lib->request('/videos/'. $id);
		
		return $videos['body'];
		
	}

}