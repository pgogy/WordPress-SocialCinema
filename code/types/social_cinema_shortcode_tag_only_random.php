<?PHP

	class social_cinema_shortcode_tag_only_random{
	
		function display($atts){
			
			global $post;
		
			require_once dirname(__FILE__) . '/../lib/social_cinema_display_library.php';
			
			$display = new social_cinema_display_library();
			
			$videos = "";
			$video_list = array();
		
			$video_list = $display->get_videos($atts);
			
			shuffle($video_list);
			
			$video = array_shift($video_list);
		
			$videos = $display->display_first_video($video);
			
			return $videos;
			
		}
	
	}