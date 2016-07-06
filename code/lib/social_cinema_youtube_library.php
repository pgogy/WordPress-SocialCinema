<?PHP

	class social_cinema_youtube_library{
	
		function setup(){
		
			require_once dirname(__FILE__) . '/Google/src/Google/Client.php';
			require_once dirname(__FILE__) . '/Google/src/Google/Service/YouTube.php';

			$DEVELOPER_KEY = 'AIzaSyBUlW4OyiPIe7LBUyMNhuSKC_1oHVAmykc';

			$this->client = new Google_Client();
			$this->client->setDeveloperKey($DEVELOPER_KEY);
			
			$this->youtube = new Google_Service_YouTube($this->client);
			
		}
	
		function get_playlist($atts){

			$this->setup();
			require_once("social_cinema_cache.php");		
			$cache = new social_cinema_cache();
		
			try {
			
				if(strpos($atts['list'],"http")!==FALSE){
					$parts = explode("?list=", $atts['list']);
					$variables = explode("&", $parts[1]);
					$list = $variables[0];
				}else{
					$list = $atts['list'];
				}

				if(!isset($atts['total']) || $atts['total'] == ""){
					$atts['total'] = 50;
				}
				
				
				if($cache->cached("youtubeplaylist-" . $list)){
					$searchResponse = $cache->get_cache("youtubeplaylist-" . $list);
				}else{
					$searchResponse = $this->youtube->playlistItems->listPlaylistItems('snippet', array(
						'playlistId' => $list,
						'maxResults' => $atts['total']
					));
					$cache->set_cache("youtubeplaylist-" . $list, $searchResponse);
				}
				
				return $searchResponse;
				
			}catch (Google_ServiceException $e) {
				$htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
				htmlspecialchars($e->getMessage()));
			}catch (Google_Exception $e) {
				$htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
				htmlspecialchars($e->getMessage()));
			}
		
		}
	
		function get_next_playlist(){

			$this->setup();
			
			try {
		
				if(strpos($_POST['list'],"http")!==FALSE){
					$url = array();
					parse_str(parse_url($_POST['list'], PHP_URL_QUERY), $url);
					if(isset($url['list'])){
						$list = $url['list'];
					}else{
						$list = $url['amp;list'];
					}
				}else{
					$list = $_POST['list'];
				}
				
				$searchResponse = $this->youtube->playlistItems->listPlaylistItems('snippet', array(
					'playlistId' => $list,
					'pageToken' => $_POST['token'],
					'maxResults' => $_POST['total']
				));

				return $searchResponse;
				
			}catch (Google_ServiceException $e) {
				$htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
				htmlspecialchars($e->getMessage()));
			}catch (Google_Exception $e) {
				$htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
				htmlspecialchars($e->getMessage()));
			}
		
		}
	
		function get_single_video($atts){
		
			$this->setup();
			require_once("social_cinema_cache.php");		
			$cache = new social_cinema_cache();
		
			try {
			
				if(strpos($atts['video'],"http")!==FALSE){
					$url = array();
					parse_str(parse_url($atts['video'], PHP_URL_QUERY), $url);
					if(isset($url['v'])){
						$id = $url['v'];
					}else{
						$id = $url['amp;v'];
					}
				}else{
					$id = $atts['video'];
				}
				
				if($cache->cached("youtubeidentifier-" . $id)){
					$searchResponse = $cache->get_cache("youtubeidentifier-" . $id);
				}else{						
					$searchResponse = $this->youtube->videos->listVideos('id,snippet', array(
						'id' => $id,
						'part' => 'id,snippet'
					));
					$cache->set_cache("youtubeidentifier-" . $id, $searchResponse);
				}
				
				return $searchResponse;
					
			}catch (Google_ServiceException $e) {
				$htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
				htmlspecialchars($e->getMessage()));
			}catch (Google_Exception $e) {
				$htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
				htmlspecialchars($e->getMessage()));
			}
		
		}
		
		function get_next_videos(){
		
			$this->setup();
			
			try {
			
				if(!isset($_POST['total']) || $_POST['total'] == ""){
					$_POST['total'] = 50;
				}

				$searchResponse = $this->youtube->search->listSearch('id,snippet', array(
				  'part' => 'snippet',
				  'q' => $_POST['term'],
				  'pageToken' => $_POST['token'],
				  'maxResults' => $_POST['total'],
				  'type' => 'video'
				));
				
				return $searchResponse;
				
			}catch (Google_ServiceException $e) {
				$htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
				htmlspecialchars($e->getMessage()));
			}catch (Google_Exception $e) {
				$htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
				htmlspecialchars($e->getMessage()));
			}
		
		}
		
		function get_same_search(){
		
			$this->setup();
			require_once("social_cinema_cache.php");		
			$cache = new social_cinema_cache();
			
			try {	

				if(!isset($_POST['total']) || $_POST['total'] == ""){
					$_POST['total'] = 50;
				}
				
				if($cache->cached("youtube-" . $_POST['term'])){
					$searchResponse = $cache->get_cache("youtube-" . $_POST['term']);
				}else{
					$searchResponse = $this->youtube->search->listSearch('id,snippet', array(
					  'q' => $_POST['term'],
					  'part' => 'snippet',
					  'maxResults' => $_POST['total'],
					  'type' => 'video'
					));
					$cache->set_cache("youtube-" . $_POST['term'], $searchResponse);
				}
				
				return $searchResponse;
				
			}catch (Google_ServiceException $e) {
				$htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
				htmlspecialchars($e->getMessage()));
			}catch (Google_Exception $e) {
				$htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
				htmlspecialchars($e->getMessage()));
			}
		
		}
	
		function search_videos($atts){
		
			$this->setup();
			require_once("social_cinema_cache.php");		
			$cache = new social_cinema_cache();
			
			try {	

				if(!isset($atts['total']) || $atts['total'] == ""){
					$atts['total'] = 50;
				}
				
				if($cache->cached("youtube-" . $atts['term'])){
					$searchResponse = $cache->get_cache("youtube-" . $atts['term']);
				}else{
					$searchResponse = $this->youtube->search->listSearch('id,snippet', array(
					  'q' => $atts['term'],
					  'part' => 'snippet',
					  'maxResults' => $atts['total'],
					  'type' => 'video'
					));
					$cache->set_cache("youtube-" . $atts['term'], $searchResponse);
				}
				
				return $searchResponse;
				
			}catch (Google_ServiceException $e) {
				$htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
				htmlspecialchars($e->getMessage()));
			}catch (Google_Exception $e) {
				$htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
				htmlspecialchars($e->getMessage()));
			}
		
		}

		function create_list_from_search($data){
		
			$videos = array();

			foreach ($data['items'] as $searchResult) {

				switch (get_class($searchResult)) {
					case 'Google_Service_YouTube_Video':
						$videos[] = array("youtube", $searchResult['videoId'], $searchResult['thumbnails']['default']['url']);
					break;
					case 'Google_Service_YouTube_SearchResult':
						$videos[] = array("youtube", $searchResult['id']['videoId'], $searchResult['snippet']['thumbnails']['default']['url']);
					break;
					case 'Google_Service_YouTube_PlaylistItem':
						$videos[] = array("youtube", $searchResult['snippet']['resourceId']['videoId'], $searchResult['snippet']['thumbnails']['default']['url']);
					break;
				}
			  
			}
			
			return $videos;
		
		}
	
	}