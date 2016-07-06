<?PHP

	class social_cinema_shortcode_video_list{
	
		function display($atts){
		
			require_once dirname(__FILE__) . '/../lib/social_cinema_youtube_library.php';			
			require_once dirname(__FILE__) . '/../lib/social_cinema_vimeo_library.php';
			require_once dirname(__FILE__) . '/../lib/social_cinema_archive_library.php';
			
			$youtube = new social_cinema_youtube_library();			
			$vimeo = new social_cinema_vimeo_library();
			$archive = new social_cinema_archive_library();
			
			$videos = "";
			$video_list = array();
			
			$list = explode(",", $atts['list']);
			
			if(!isset($atts['columns'])){
				$atts['columns'] = 2;
			}
			
			$width = (90 / $atts['columns']);
			
			foreach($list as $item){
			
				if(strpos($item,"youtube")!==FALSE){
					$atts['video'] = $item;				
					$searchResponse = $youtube->get_single_video($atts);
					$videos .= '<div class="social-cinema-holder-wall" style="width: ' . $width . '%"><iframe id="social-cinema-current" video_type="youtube" src="//youtube.com/embed/' . $searchResponse['items'][0]['id'] . '" video_id="' . $searchResponse['items'][0]['id'] . '" video_image="' . $searchResponse['items'][0]['snippet']['thumbnails']['default']['url'] . '"></iframe></div>';
				}
				
				if(strpos($item,"vimeo")!==FALSE){
					$atts['video'] = $item;
					$video = $vimeo->get_single_video($atts);
					$videos .= '<div class="social-cinema-holder-wall" style="width: ' . $width . '%"><iframe id="social-cinema-current" video_type="vimeo" src="//player.vimeo.com' . str_replace("videos", "video", $video['uri']) . '" video_id="' . str_replace("videos", "video", $video['uri']) . '" video_image="' . $video['pictures']['sizes'][0]['link'] . '"></iframe></div>';
				}
				
				if(strpos($item,"archive")!==FALSE){
					$atts['video'] = $item;
					$video = $archive->get_single_video($atts);		
					$videos .= '<div class="social-cinema-holder-wall" style="width: ' . $width . '%"><video style="width: 100%" id="social-cinema-current" video_type="archive" video_id="' . $video[0][1] . '" video_image="' . $video[0][2] . '" controls>';
					foreach($video as $src){
						$videos .= '<source src="' . $src[1] . '">';
					}
					$videos .= '</video></div>';
				}
			
			}
		
			return $videos . "</div>";
			
		}
	
	}