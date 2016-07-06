<?PHP

	class social_cinema_shortcode_youtube_playlist_only_wall{
	
		function display($atts){
	
			global $post;
	
			require_once dirname(__FILE__) . '/../lib/social_cinema_youtube_library.php';
			require_once dirname(__FILE__) . '/../lib/social_cinema_display_library.php';
			
			$youtube = new social_cinema_youtube_library();
			$display = new social_cinema_display_library();
			
			$searchResponse = $youtube->get_playlist($atts);
			
			$video_list = $youtube->create_list_from_search($searchResponse);
				
			$videos = $display->display_dummy_video();
			
			$videos .= "<div class='other_social_cinema_videos' id='other_social_cinema_videos_" . $post->ID . "'>";
			
			$new_items = array_slice($video_list ,1,$atts['total']-1);
			
			foreach ($video_list as $video) {
			
				$videos .= '<img post_id="' . $post->ID . '" video_type="' . $video[0] . '" video_id="' . $video[1] . '" src="' . $video[2] . '" />';
			  
			}
		
			return $videos . "</div>";
			
		}
	
	}