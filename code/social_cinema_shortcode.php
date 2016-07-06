<?PHP	

	class social_cinema_shortcode{

		function __construct(){
		
			add_shortcode("social_cinema", array($this, "display"));
			$types_dir = opendir(dirname(__FILE__) . "/types");
			while($file = readdir($types_dir)){
				if($file!="."&&$file!=".."){
					require_once(dirname(__FILE__) . "/types/" . $file);
					$class = str_replace(".php", "", $file);
					$shortcode = new $class();
					if(is_callable(array($shortcode,"add_ajax"))){
						$shortcode->add_ajax();
					}	
				}
			}
			
		}
		
		function display($atts){
		
			switch($atts['type']){
			
				case "featured_and_tag" : 	require_once(dirname(__FILE__) . "/types/social_cinema_shortcode_" . $atts['type'] . ".php");
											$class = "social_cinema_shortcode_" . $atts['type'];
											$shortcode = new $class(); break;
											
				case "featured_and_tag_load" : 	require_once(dirname(__FILE__) . "/types/social_cinema_shortcode_" . $atts['type'] . ".php");
												$class = "social_cinema_shortcode_" . $atts['type'];
												$shortcode = new $class(); 
												break;
											
				case "tag_only" :		 	require_once(dirname(__FILE__) . "/types/social_cinema_shortcode_" . $atts['type'] . ".php");
											$class = "social_cinema_shortcode_" . $atts['type'];
											$shortcode = new $class(); break;
											
				case "tag_only_wall" :		require_once(dirname(__FILE__) . "/types/social_cinema_shortcode_" . $atts['type'] . ".php");
											$class = "social_cinema_shortcode_" . $atts['type'];
											$shortcode = new $class(); break;
											
				case "tag_only_wall_load" :		require_once(dirname(__FILE__) . "/types/social_cinema_shortcode_" . $atts['type'] . ".php");
												$class = "social_cinema_shortcode_" . $atts['type'];
												$shortcode = new $class(); break;
											
				case "tag_only_random" :	require_once(dirname(__FILE__) . "/types/social_cinema_shortcode_" . $atts['type'] . ".php");
											$class = "social_cinema_shortcode_" . $atts['type'];
											$shortcode = new $class(); break;
											
				case "tag_only_random_load" :	require_once(dirname(__FILE__) . "/types/social_cinema_shortcode_" . $atts['type'] . ".php");
												$class = "social_cinema_shortcode_" . $atts['type'];
												$shortcode = new $class(); break;
												
				case "youtube_playlist" :	require_once(dirname(__FILE__) . "/types/social_cinema_shortcode_" . $atts['type'] . ".php");
											$class = "social_cinema_shortcode_" . $atts['type'];
											$shortcode = new $class(); break;
											
				case "youtube_playlist_only_wall" :		require_once(dirname(__FILE__) . "/types/social_cinema_shortcode_" . $atts['type'] . ".php");
														$class = "social_cinema_shortcode_" . $atts['type'];
														$shortcode = new $class(); break;
										
				case "youtube_playlist_only_wall_load" :	require_once(dirname(__FILE__) . "/types/social_cinema_shortcode_" . $atts['type'] . ".php");
															$class = "social_cinema_shortcode_" . $atts['type'];
															$shortcode = new $class(); break;
											
				case "youtube_playlist_load" :		require_once(dirname(__FILE__) . "/types/social_cinema_shortcode_" . $atts['type'] . ".php");
													$class = "social_cinema_shortcode_" . $atts['type'];
													$shortcode = new $class(); break;
											
				case "youtube_playlist_random" : 	require_once(dirname(__FILE__) . "/types/social_cinema_shortcode_" . $atts['type'] . ".php");
													$class = "social_cinema_shortcode_" . $atts['type'];
													$shortcode = new $class(); break;
											
				case "youtube_playlist_random_load" : 	require_once(dirname(__FILE__) . "/types/social_cinema_shortcode_" . $atts['type'] . ".php");
														$class = "social_cinema_shortcode_" . $atts['type'];
														$shortcode = new $class(); break;
														
				case "video_list" : 	require_once(dirname(__FILE__) . "/types/social_cinema_shortcode_" . $atts['type'] . ".php");
										$class = "social_cinema_shortcode_" . $atts['type'];
										$shortcode = new $class(); break;	
											
				default : break;
			
			}
			
			return $shortcode->display($atts);
		
		}
	
	}
	
	$social_cinema_shortcode = new social_cinema_shortcode();
