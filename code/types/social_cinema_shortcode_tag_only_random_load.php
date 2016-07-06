<?PHP

	class social_cinema_shortcode_tag_only_random_load{
	
		function add_ajax(){
		
			add_action( 'wp_ajax_social-cinema-load-more-tag-random', array($this, 'load_more') );
			add_action( 'wp_ajax_nopriv_social-cinema-load-more-tag-random', array($this, 'load_more') );
	
		}
		
		function load_more(){
		
			if(wp_verify_nonce($_POST['nonce'],"wordpress-social-cinema-load-more")){
			
				require_once dirname(__FILE__) . '/../lib/social_cinema_youtube_library.php';			
				require_once dirname(__FILE__) . '/../lib/social_cinema_vimeo_library.php';
				require_once dirname(__FILE__) . '/../lib/social_cinema_archive_library.php';
				
				$youtube = new social_cinema_youtube_library();				
				$vimeo = new social_cinema_vimeo_library();
				$archive = new social_cinema_archive_library();
				
				$videos = "";
				$video_list = array();
			
				if(!isset($_POST['source'])){
					$_POST['source'] = "youtube";
				}
			
				if(strpos($_POST['source'],"youtube")!==FALSE){
					$searchResponse = $youtube->get_same_search();				
					$youtube_list = $youtube->create_list_from_search($searchResponse);
				}else{
					$youtube_list = array();
				}			
				$video_list = array_merge($video_list, $youtube_list);
				
				if(strpos($_POST['source'],"vimeo")!==FALSE){
					$vimeo_list = $vimeo->get_same_search();
				}else{
					$vimeo_list = array();
				}
				$video_list = array_merge($video_list, $vimeo_list);
				
				if(strpos($_POST['source'],"archive")!==FALSE){
					$archive_list = $archive->get_same_search();
					$archive_list = array_slice($archive_list, 1, 50);
				}else{
					$archive_list = array();
				}
				$video_list = array_merge($video_list, $archive_list);
				
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
		
			$video_list = $display->get_videos($atts);
			
			shuffle($video_list);
			
			$video = array_shift($video_list);
		
			$videos = $display->display_first_video($video);
			
			return $videos . "<button post_id='" . $post->ID . "' action='social-cinema-load-more-tag-random' next_page_number='1' source='" . $atts['source'] . "' term='" . $atts['term'] . "' class='social_cinema_load_more_tag'>Load Another</button></div>";
			
		}
	
	}