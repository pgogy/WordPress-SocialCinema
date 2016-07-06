<?PHP

	class social_cinema_shortcode_tag_only{
	
		function display($atts){
			
			global $post;
		
			require_once dirname(__FILE__) . '/../lib/social_cinema_display_library.php';
			
			$display = new social_cinema_display_library();
			
			$videos = "";
			$video_list = array();
		
			$video_list = $display->get_videos($atts);
		
			$video = array_shift($video_list);
			
			$videos = $display->display_first_video($video);
			
			$videos .= "<div class='other_social_cinema_videos' id='other_social_cinema_videos_" . $post->ID . "'>";
			
			foreach ($video_list as $video) {
			
				$videos .= '<img post_id="' . $post->ID . '" video_type="' . $video[0] . '" video_id="' . $video[1] . '" src="' . $video[2] . '" />';
			  
			}
		
			return $videos . "</div>";
			
		}
	
	}