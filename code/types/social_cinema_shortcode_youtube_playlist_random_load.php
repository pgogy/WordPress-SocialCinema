<?PHP

	class social_cinema_shortcode_youtube_playlist_random_load{
	
		function add_ajax(){
		
			add_action( 'wp_ajax_social-cinema-load-more-playlist-random', array($this, 'load_more') );
			add_action( 'wp_ajax_nopriv_social-cinema-load-more-playlist-random', array($this, 'load_more') );
	
		}
		
		function load_more(){
		
			if(wp_verify_nonce($_POST['nonce'],"wordpress-social-cinema-load-more")){
			
				require_once dirname(__FILE__) . '/../lib/social_cinema_youtube_library.php';
				
				$youtube = new social_cinema_youtube_library();
				
				$searchResponse = $youtube->get_playlist($_POST);
				
				$video_list = $youtube->create_list_from_search($searchResponse);
				
				$random = rand(0, (count($video_list)-1));
					
				$videos = new StdClass();
				$videos->data = array();
				
				foreach($video_list as $video){
				
					$videos->data[] = array("youtube", $video_list[$random][1], $video_list[$random][2]);
					
				}
				
			}
			
			echo json_encode($videos, JSON_PRETTY_PRINT);
		
			die();
		
		}
	
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
	
			return $videos . "<button post_id='" . $post->ID . "' action='social-cinema-load-more-playlist-random' list='" . $atts['list'] . "' next_page='" . $searchResponse['nextPageToken'] . "' page_total='" . $atts['total'] . "' class='social_cinema_load_more_tag'>Load Another</button></div></div>";
			
		}
	
	}