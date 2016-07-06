<?PHP	

	class social_cinema_js{

		function __construct(){
		
			add_action( 'wp_enqueue_scripts', array($this, 'scripts') );
			
		}
		
		function scripts(){
		
			global $post;
			
			if(strpos($post->post_content, "[social_cinema")!==FALSE){
			
				//if(strpos($post->post_content, "featured_and_tag")!==FALSE || strpos($post->post_content, "playlist")!==FALSE || strpos($post->post_content, "tag_only")!==FALSE){
			
					wp_enqueue_script( 'wordpress-social-cinema-resize', plugin_dir_url(__FILE__) . '../js/social-cinema-resize.js', array('jquery'), array(), '20131205', true );
					wp_enqueue_script( 'wordpress-social-cinema-click', plugin_dir_url(__FILE__) . '../js/social-cinema-click.js', array('jquery'), array(), '20131205', true );
					wp_enqueue_script( 'wordpress-social-cinema-load-more', plugin_dir_url(__FILE__) . '../js/social-cinema-load-more.js', array('jquery'), array(), '20131205', true );
					wp_localize_script( 'wordpress-social-cinema-load-more', 'wordpress_social_cinema_load_more', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'answerNonce' => wp_create_nonce( 'wordpress-social-cinema-load-more' ) ) );

				
				//}
			
			}

		}
	
	}
	
	$social_cinema_js = new social_cinema_js();
