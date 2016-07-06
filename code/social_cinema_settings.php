<?PHP

	class social_cinema_settings{
	
		/**
		 * Holds the values to be used in the fields callbacks
		 */
		private $options;

		/**
		 * Start up
		 */
		public function __construct(){
			add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
			add_action( 'admin_init', array( $this, 'page_init' ) );
		}
		
		public function add_plugin_page(){
		
			add_menu_page( 'Social Cinema', 'Social Cinema', 'manage_options', 'social-cinema-settings', array($this, 'main_page'));
			add_submenu_page( 'social-cinema-settings', 'Cache Settings', 'Cache Settings', 'manage_options', 'social-cinema-cache', array($this, 'cache_page'));
			add_submenu_page( 'social-cinema-settings', 'Purge Cache', 'Purge Cache', 'manage_options', 'social-cinema-cache-purge', array($this, 'cache_purge_page'));
			
		}
		
		public function cache_purge_page(){
		
			$cache_path = plugin_dir_path( __FILE__ );
			$cache_dir = opendir($cache_path . "../cache/");
			while($file = readdir($cache_dir)){
				if($file != "." && $file != ".."){
					unlink($cache_path . "../cache/" . $file);
				}
			}
		
			?><h2>Cache purge</h2><p>Cache has been purged</p><?PHP
		}
		
		public function main_page(){
			?><h1>Social Cinema</h1>
			<p>Social cinema allows you to display video collections from Archive.org, Vimeo and Youtube</p>
			<p>The following are available as a shortcode - so copy and paste the examples and change the values</p>
			<h2>featured_and_tag</h2>
			<p>This loads one featured video and then searches for a keyword and loads up to a certain number of those videos</p>
			<p><strong>Example</strong></p>
			<p>[social_cinema type='featured_and_tag' video='url of video to feature' source='archive,vimeo,youtube' term='love' total='50']</p>
			<p>'video' is the url for the featured video. 'source' are the sites to search (vimeo, youtube or archive.org). 'term' is the search term. 'total' is the number of videos to return</p>
			<h2>featured_and_tag_load</h2>
			<p>As above, but provides a load more button to bring up more videos</p>
			<p><strong>Example</strong></p>
			<p>[social_cinema type='featured_and_tag_load' video='url of video to feature' source='archive,vimeo,youtube' term='love' total='50']</p>
			<h2>tag_only</h2>
			<p>This searches for a keyword and loads up to a certain number of those videos</p>
			<p><strong>Example</strong></p>
			<p>[social_cinema type='tag_only' source='archive,vimeo,youtube' term='love' total='50']</p>
			<p>'source' are the sites to search (vimeo, youtube or archive.org). 'term' is the search term. 'total' is the number of videos to return</p>
			<h2>tag_only_wall</h2>
			<p>As above, but no large video is present at the top, just the video wall.</p>
			<p><strong>Example</strong></p>
			<p>[social_cinema type='tag_only_wall' source='archive,vimeo,youtube' term='love' total='50']</p>
			<p>'source' are the sites to search (vimeo, youtube or archive.org). 'term' is the search term. 'total' is the number of videos to return</p>	
			<h2>tag_only_wall_load</h2>
			<p>As above, but no large video is present at the top, just the video wall. A button is presented to load more</p>
			<p><strong>Example</strong></p>
			<p>[social_cinema type='tag_only_wall_load' source='archive,vimeo,youtube' term='love' total='50']</p>
			<h2>tag_only_random</h2>
			<p>This one seaches for a term and then displays one at random</p>
			<p><strong>Example</strong></p>
			<p>[social_cinema type='tag_only_random' source='archive,vimeo,youtube' term='love']</p>
			<h2>tag_only_random_load</h2>
			<p>This one seaches for a term and then displays one at random, with the option to load another</p>
			<p><strong>Example</strong></p>
			<p>[social_cinema type='tag_only_random_load' source='archive,vimeo,youtube' term='love']</p>
			<h2>youtube_playlist</h2>
			<p>This loads one youtube playlist and up to a certain number of those videos</p>
			<p><strong>Example</strong></p>
			<p>[social_cinema type='youtube_playlist' list='url of playlist to feature' total='50']</p>
			<p>'list' is the url for the playlist. 'total' is the number of videos to return</p>
			<h2>youtube_playlist_load</h2>
			<p>As above, but features a load button</p>
			<p><strong>Example</strong></p>
			<p>[social_cinema type='youtube_playlist_load' list='url of playlist to feature' total='50']</p>
			<h2>youtube_playlist_only_wall</h2>
			<p>Loads a playlist but doesn't display a large video until one on the wall is clicked</p>
			<p><strong>Example</strong></p>
			<p>[social_cinema type='youtube_playlist_only_wall' list='url of playlist to feature' total='50']</p>
			<p>'list' is the url for the playlist. 'total' is the number of videos to return</p>
			<h2>youtube_playlist_only_wall_load</h2>
			<p>As above, but gives an option to load more</p>
			<p><strong>Example</strong></p>
			<p>[social_cinema type='youtube_playlist_only_wall' list='url of playlist to feature' total='50']</p>
			<p>'list' is the url for the playlist. 'total' is the number of videos to return</p>
			<h2>youtube_playlist_random</h2>
			<p>Loads one item from a Youtube Playlist</p>
			<p><strong>Example</strong></p>
			<p>[social_cinema type='youtube_playlist_random' list='url of playlist to feature']</p>
			<p>'list' is the url for the playlist.</p>
			<h2>youtube_playlist_random_load</h2>
			<p>Loads one item from a Youtube Playlist, but gives an option to load another</p>
			<p><strong>Example</strong></p>
			<p>[social_cinema type='youtube_playlist_random_load' list='url of playlist to feature']</p>
			<p>'list' is the url for the playlist.</p>
			<h2>video_list</h2>
			<p>Loads a series of videos from urls and displays them</p>
			<p><strong>Example</strong></p>
			<p>[social_cinema type='video_list' list='url1,url2,url3' columns="2"]</p>
			<p>'list' is a series of urls for videos from archive.org, vimeo and youtube - separate the urls with a comma. 'columns' is how many columns to have on the display</p>
			<?PHP
		}
		
		public function cache_page(){
		
			// Set class property
			$this->options = get_option( 'social-cinema-cache' );
			
			?>
			<div class="wrap">           
				<form method="post" action="options.php">
				<?php
					// This prints out all hidden setting fields
					settings_fields( 'social-cinema-settings' );   
					do_settings_sections( 'social-cinema-cache' );
					submit_button(); 
				?>
				</form>
			</div>
			<?php
			
		}
		
		public function page_init(){   

			register_setting(
				'social-cinema-settings', // Option group
				'social-cinema-cache', // Option name
				array( $this, 'sanitize' ) // Sanitize
			);

			add_settings_section(
				'setting_section_id', // ID
				'Social Cinema Cache Settings', // Title
				array( $this, 'cache_page_info' ), // Callback
				'social-cinema-cache' // Page
			);

			add_settings_field(
				'youtube-cache-duration', // ID
				'Youtube Cache Duration', // Title 
				array( $this, 'youtube_cache_page_display' ), // Callback
				'social-cinema-cache', // Page
				'setting_section_id' // Section           
			);
			
			add_settings_field(
				'vimeo-cache-duration', // ID
				'Vimeo Cache Duration', // Title 
				array( $this, 'vimeo_cache_page_display' ), // Callback
				'social-cinema-cache', // Page
				'setting_section_id' // Section           
			);
			
			add_settings_field(
				'archive-cache-duration', // ID
				'Archive Cache Duration', // Title 
				array( $this, 'archive_cache_page_display' ), // Callback
				'social-cinema-cache', // Page
				'setting_section_id' // Section           
			);
			
		}
		
		public function youtube_cache_page_display(){	

			if (!isset($this->options['youtube-cache-duration'])){
				$this->options['youtube-cache-duration'] = 'Enter a time here in seconds';
			}

			$content = html_entity_decode(esc_attr($this->options['youtube-cache-duration']));
			
			?><label>Cache Duration in seconds</label><input type="text" name="social-cinema-cache[youtube-cache-duration]" size="60" value="<?PHP echo $content; ?>" /><?PHP
		
		}
		
		public function vimeo_cache_page_display(){	
		
			if (!isset($this->options['vimeo-cache-duration'])){
				$this->options['vimeo-cache-duration'] = 'Enter a time here in seconds';
			}

			$content = html_entity_decode(esc_attr($this->options['vimeo-cache-duration']));
			
			?><label>Cache Duration in seconds</label><input type="text" name="social-cinema-cache[vimeo-cache-duration]" size="60" value="<?PHP echo $content; ?>" /><?PHP
		
		}
		
		public function archive_cache_page_display(){	

			if (!isset($this->options['archive-cache-duration'])){
				$this->options['archive-cache-duration'] = 'Enter a time here in seconds';
			}

			$content = html_entity_decode(esc_attr($this->options['archive-cache-duration']));
			
			?><label>Cache Duration in seconds</label><input type="text" name="social-cinema-cache[archive-cache-duration]" size="60" value="<?PHP echo $content; ?>" /><?PHP
		
		}
		
		public function cache_page_info(){
			?>Use this page to set up cache for Social Cinema<?PHP
		}
		
		
		public function sanitize( $input ){
			return $input;
		}
	
	}
	
	$social_cinema_settings = new social_cinema_settings;
	