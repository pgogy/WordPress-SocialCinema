<?PHP

	class social_cinema_shortcode_youtube_playlist_random{
	
		function display($atts){
		
			global $post;
	
			require_once dirname(__FILE__) . '/../lib/social_cinema_youtube_library.php';
			require_once dirname(__FILE__) . '/../lib/social_cinema_display_library.php';
			
			$youtube = new social_cinema_youtube_library();
			$display = new social_cinema_display_library();

			$atts['total'] = 50;
			
			$searchResponse = $youtube->get_playlist($atts);
			
			$video_list = $youtube->create_list_from_search($searchResponse);
			
			shuffle($video_list);
				
			$video = array_shift($video_list);
		
			$videos = $display->display_first_video($video);
	
			return $videos . "</div>";
			
		}
	
	}