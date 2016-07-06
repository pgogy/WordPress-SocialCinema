<?PHP

	class social_cinema_shortcode_youtube_playlist{
	
		function display($atts){
		
			global $post;
	
			require_once dirname(__FILE__) . '/../lib/social_cinema_youtube_library.php';
			
			$youtube = new social_cinema_youtube_library();
			
			$searchResponse = $youtube->get_playlist($atts);
			
			$video_list = $youtube->create_list_from_search($searchResponse);
				
			switch($video_list[0][0]){
				case "youtube" :
					$videos = '<div class="social-cinema-holder"><div id="social-cinema-current-video-' . $post->ID . '"><iframe id="social-cinema-current-' . $post->ID . '" src="https://youtube.com/embed/' . $video_list[0][1] . '" video_id="' . $video_list[0][1] . '" video_image="' . $video_list[0][2] . '"></iframe></div>';
			}
			
			$videos .= "<div class='other_social_cinema_videos' id='other_social_cinema_videos_" . $post->ID . "'>";
			
			$new_items = array_slice($video_list ,1,$atts['total']-1);
			
			foreach ($video_list as $video) {
			
				$videos .= '<img post_id="' . $post->ID . '" video_type="' . $video[0] . '" video_id="' . $video[1] . '" src="' . $video[2] . '" />';
			  
			}
		
			return $videos . "</div></div>";
			
		}
	
	}