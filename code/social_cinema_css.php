<?PHP	

	class social_cinema_css{

		function __construct(){
		
			add_action( 'wp_enqueue_scripts', array($this, 'styles') );
			
		}
		
		function styles(){
		
			wp_enqueue_style( 'wordpress-social-cinema', plugin_dir_url(__FILE__) . '../css/social-cinema.css', array(), '1' );
			
		}
	
	}
	
	$social_cinema_css = new social_cinema_css();
