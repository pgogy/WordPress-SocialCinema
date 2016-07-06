<?php

class social_cinema_display_library{

	function get_videos_and_page($atts){
	
		return $this->get_videos_list($atts);
	
	}

	function get_videos($atts){
	
		$data = $this->get_videos_list($atts);
		return $data[0];
	
	}

	function get_videos_list($atts){
	
		global $post;
	
		require_once dirname(__FILE__) . '/social_cinema_youtube_library.php';			
		require_once dirname(__FILE__) . '/social_cinema_vimeo_library.php';
		require_once dirname(__FILE__) . '/social_cinema_archive_library.php';
		
		$youtube = new social_cinema_youtube_library();			
		$vimeo = new social_cinema_vimeo_library();
		$archive = new social_cinema_archive_library();
	
		$video_list = array();
	
		if(!isset($atts['source'])){
			$atts['source'] = "youtube";
		}
		
		$searchResponse = "";
		
		if(!isset($atts['total'])){
			$atts['total'] = 50;
		}
	
		if(strpos($atts['source'],"youtube")!==FALSE){
			$searchResponse = $youtube->search_videos($atts);			
			$youtube_list = $youtube->create_list_from_search($searchResponse);
		}else{
			$youtube_list = array();
		}			
		$video_list = array_merge($video_list, $youtube_list);
		
		if(strpos($atts['source'],"vimeo")!==FALSE){
			$vimeo_list = $vimeo->search($atts);
		}else{
			$vimeo_list = array();
		}
		$video_list = array_merge($video_list, $vimeo_list);
		
		if(strpos($atts['source'],"archive")!==FALSE){
			$archive_list = $archive->search($atts);
			$archive_list = array_slice($archive_list, 0, $atts['total']);
		}else{
			$archive_list = array();
		}
		$video_list = array_merge($video_list, $archive_list);
		
		shuffle($video_list);
		
		$video_list = array_slice($video_list,1,($atts['total']-1));
			
		return array($video_list, $searchResponse);
		
	}
	
	function display_first_video($video){
			
		global $post;	
		
		switch($video[0]){
			case "youtube" : 	$videos = '<div class="social-cinema-holder"><div id="social-cinema-current-video-' . $post->ID . '"><iframe id="social-cinema-current-' . $post->ID . '" video_type="youtube" src="//youtube.com/embed/' . $video[1] . '" video_id="' . $video[1] . '" video_image="' . $video[2] . '"></iframe></div>';				
								break;
			case "vimeo"   :	$videos = '<div class="social-cinema-holder"><div id="social-cinema-current-video-' . $post->ID . '"><iframe id="social-cinema-current-' . $post->ID . '" video_type="vimeo" src="//player.vimeo.com' . $video[1] . '" video_id="' . $video[1] . '" video_image="' . $video[2] . '"></iframe></div>';				
								break;
			case "archive" :	$videos = '<div class="social-cinema-holder"><div id="social-cinema-current-video-' . $post->ID . '"><video style="width: 100%" id="social-cinema-current-' . $post->ID . '" video_type="archive" video_id="' . $video[1] . '" video_image="' . $video[2] . '" controls>';
								$videos .= '<source src="' . $video[1] . '">';
								$videos .= '</video></div></div>';
								break;
		}
		
		return $videos;
		
	}
	
	function get_featured_video($atts){
			
		global $post;
				
		require_once dirname(__FILE__) . '/social_cinema_youtube_library.php';			
		require_once dirname(__FILE__) . '/social_cinema_vimeo_library.php';
		require_once dirname(__FILE__) . '/social_cinema_archive_library.php';
		
		$youtube = new social_cinema_youtube_library();			
		$vimeo = new social_cinema_vimeo_library();
		$archive = new social_cinema_archive_library();
		
		$videos = "";
	
		if(strpos($atts['video'],"youtube")!==FALSE){			
			$searchResponse = $youtube->get_single_video($atts);
			if(count($searchResponse['items'][0])){
				$videos = '<div class="social-cinema-holder"><div id="social-cinema-current-video-' . $post->ID . '"><iframe id="social-cinema-current-' . $post->ID . '" video_type="youtube" src="//youtube.com/embed/' . $searchResponse['items'][0]['id'] . '" video_id="' . $searchResponse['items'][0]['id'] . '" video_image="' . $searchResponse['items'][0]['snippet']['thumbnails']['default']['url'] . '"></iframe></div>';	
			}else{
				$videos = '<div class="social-cinema-holder"><div id="social-cinema-current-video-' . $post->ID . '"><div id="social-cinema-current-' . $post->ID . '"><p>Error finding video at that URL</p></div></div></div>';	
			}			
		}
		
		if(strpos($atts['video'],"vimeo")!==FALSE){			
			$video = $vimeo->get_single_video($atts);
			$videos = '<div class="social-cinema-holder"><div id="social-cinema-current-video-' . $post->ID . '"><iframe id="social-cinema-current-' . $post->ID . '" video_type="vimeo" src="//player.vimeo.com' . str_replace("videos", "video", $video['uri']) . '" video_id="' . str_replace("videos", "video", $video['uri']) . '" video_image="' . $video['pictures']['sizes'][0]['link'] . '"></iframe></div>';
		}
		
		if(strpos($atts['video'],"archive")!==FALSE){
			$video = $archive->get_single_video($atts);		
			$videos .= '<div class="social-cinema-holder"><div id="social-cinema-current-video-' . $post->ID . '"><video style="width: 100%" id="social-cinema-current-' . $post->ID . '" video_type="archive" video_id="' . $video[0][1] . '" video_image="' . $video[0][2] . '" controls>';
			foreach($video as $src){
				$videos .= '<source src="' . $src[1] . '">';
			}
			$videos .= '</video></div></div>';
		}
		
		return $videos;
		
	}
	
	function display_dummy_video(){
			
		global $post;
				
		$videos = '<div class="social-cinema-holder">
						<div class="social-cinema-dummy" id="social-cinema-current-' . $post->ID . '" style="display:none">
							<div id="social-cinema-current-video-' . $post->ID . '">
							</div>
						</div>
					';	
		
		return $videos;
		
	}
	
	
}