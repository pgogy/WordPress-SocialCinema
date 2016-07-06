<?PHP

	class social_cinema_shortcode_youtube_playlist_only_wall_load{
	
		function add_ajax(){
		
			add_action( 'wp_ajax_social-cinema-load-more-playlist-wall', array($this, 'load_more') );
			add_action( 'wp_ajax_nopriv_social-cinema-load-more-playlist-wall', array($this, 'load_more') );
	
		}
		
		function load_more(){
		
			if(wp_verify_nonce($_POST['nonce'],"wordpress-social-cinema-load-more")){
			
				require_once dirname(__FILE__) . '/../lib/social_cinema_youtube_library.php';
				
				$youtube = new social_cinema_youtube_library();
				
				$searchResponse = $youtube->get_next_playlist($_POST);

				$videos = new StdClass();
				$videos->next_page = $searchResponse['nextPageToken'];
				$videos->data = array();
				
				$video_list = $youtube->create_list_from_search($searchResponse);
				
				foreach ($video_list as $video) {
				
					$videos->data[] = array($video[0], $video[1], $video[2]);
				  
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
			
			$searchResponse = $youtube->get_playlist($atts);
			
			$video_list = $youtube->create_list_from_search($searchResponse);
				
			$videos = $display->display_dummy_video();
			
			$videos .= "<div class='other_social_cinema_videos' id='other_social_cinema_videos_" . $post->ID . "'>";
			
			$new_items = array_slice($video_list ,1,$atts['total']-1);
			
			foreach ($video_list as $video) {
			
				$videos .= '<img post_id="' . $post->ID . '" video_type="' . $video[0] . '" video_id="' . $video[1] . '" src="' . $video[2] . '" />';
			  
			}
		
			return $videos . "</div><button post_id='" . $post->ID . "' action='social-cinema-load-more-playlist-wall' list='" . $atts['list'] . "' next_page='" . $searchResponse['nextPageToken'] . "' page_total='" . $atts['total'] . "' class='social_cinema_load_more_tag'>Load More</button>";
			
		}
	
	}