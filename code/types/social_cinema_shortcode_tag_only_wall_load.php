<?PHP

	class social_cinema_shortcode_tag_only_wall_load{
	
		function add_ajax(){
		
			add_action( 'wp_ajax_social-cinema-load-more-tag-wall', array($this, 'load_more') );
			add_action( 'wp_ajax_nopriv_social-cinema-load-more-tag-wall', array($this, 'load_more') );
	
		}
		
		function load_more(){
		
			if(wp_verify_nonce($_POST['nonce'],"wordpress-social-cinema-load-more")){
			
				require_once dirname(__FILE__) . '/../lib/social_cinema_youtube_library.php';
			
				require_once dirname(__FILE__) . '/../lib/social_cinema_vimeo_library.php';
				
				$youtube = new social_cinema_youtube_library();
				
				$vimeo = new social_cinema_vimeo_library();
				
				$videos = "";
				$video_list = array();
			
				if(!isset($_POST['source'])){
					$_POST['source'] = "youtube";
				}
			
				if(strpos($_POST['source'],"youtube")!==FALSE){
					$searchResponse = $youtube->get_next_videos();				
					$youtube_list = $youtube->create_list_from_search($searchResponse);
				}else{
					$youtube_list = array();
				}			
				$video_list = array_merge($youtube_list, $video_list);
				
				if(strpos($_POST['source'],"vimeo")!==FALSE){
					$vimeo_list = $vimeo->get_next_search();
				}else{
					$vimeo_list = array();
				}
				$video_list = array_merge($youtube_list, $vimeo_list);
				
				shuffle($video_list);				
					
				$videos = new StdClass();
				$videos->next_page = $searchResponse['nextPageToken'];
				$videos->data = array();
				
				foreach ($video_list as $searchResult) {
				
					$videos->data[] = array($searchResult[0], $searchResult[1], $searchResult[2]);
			  
				}
				
			}
			
			echo json_encode($videos, JSON_PRETTY_PRINT);
		
			die();
		
		}
	
		function display($atts){
		
			global $post;
		
			require_once dirname(__FILE__) . '/../lib/social_cinema_display_library.php';
			
			$display = new social_cinema_display_library();
			
			$videos = "";
			$video_list = array();
		
			$video_data = $display->get_videos_and_page($atts);
			$video_list = $video_data[0];
		
			$videos = $display->display_dummy_video();
			
			$videos .= "<div class='other_social_cinema_videos' id='other_social_cinema_videos_" . $post->ID . "'>";
			
			foreach ($video_list as $video) {
			
				$videos .= '<img post_id="' . $post->ID . '" video_type="' . $video[0] . '" video_id="' . $video[1] . '" src="' . $video[2] . '" />';
			  
			}
		
			return $videos . "</div><button post_id='" . $post->ID . "' term='" . $atts['term'] . "' next_page_number='1' source='" . $atts['source'] . "' action='social-cinema-load-more-tag-wall' next_page='" . $video_data[1]['nextPageToken'] . "' page_total='" . $atts['total'] . "' class='social_cinema_load_more_tag'>Load More</button>";
			
		}
	
	}