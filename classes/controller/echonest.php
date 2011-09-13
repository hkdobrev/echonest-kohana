<?php defined('SYSPATH') or die('No direct access allowed!');

class Controller_Echonest extends Controller{
	
	public function before()
	{
		parent::before();
		$this->api_key = Kohana::$config->load('echonest')->get('api_key');
		$this->result = '';
		$this->artist_name = 'oz';
		$this->echonest = Echonest::instance();
		$this->session = Session::instance();
	}
	
	protected function _get_artist_id($artists, $artists_count){
		$artist_id = false;
		$i = -1;
		while($i < $artists_count - 1 && !$artist_id)
		{
			$i = $i + 1;
			$artist = $artists[$i];
			if(strtolower($this->artist_name) === strtolower($artist['name'])){
				$artist_id = $artist['id'];
			}
		}
		return $artist_id;
	}
	
	public function action_dynamic(){
		$artist = str_replace('_', ' ', $this->request->param('artist', $this->artist_name));
		if($artist){
			$playlist_api = $this->echonest->getPlaylistApi();
			$dynamic_playlist = $playlist_api->getDynamic(array('artist' => $artist, 'type' => 'artist-radio'));
			$this->session->set('dynamic_playlist_id', $dynamic_playlist['session_id']);
			$this->result = $dynamic_playlist['songs'];
		}
	}
	
	public function action_dynamic_rate(){
		$rating = $this->request->param('rating', 5);
		if(($session_id = $this->session->get('dynamic_playlist_id'))){
			$playlist_api = $this->echonest->getPlaylistApi();
			$next = $playlist_api->getDynamic(array('session_id' => $session_id, 'rating' => $rating));
			$this->session->set('dynamic_playlist_id', $next['session_id']);
			$this->result = $next['songs'];
		} else {
			throw new Kohana_HTTP_Exception_400('There is no active dynamic playlist for the current user!');
		}
	}
	
	public function action_dynamic_tempo(){
		$tempo = $this->request->param('tempo', 50) / 50;
		if(($session_id = $this->session->get('dynamic_playlist_id'))){
			$playlist_api = $this->echonest->getPlaylistApi();
			$next = $playlist_api->getDynamic(array('session_id' => $session_id, 'steer' => 'tempo^' . $tempo));
			$this->session->set('dynamic_playlist_id', $next['session_id']);
			$this->result = $next['songs'];
		} else {
			throw new Kohana_HTTP_Exception_400('There is no active dynamic playlist for the current user!');
		}
		
	}
	
	
	public function action_dynamic_next(){
		if(($session_id = $this->session->get('dynamic_playlist_id'))){
			$playlist_api = $this->echonest->getPlaylistApi();
			$next = $playlist_api->getDynamic(array('session_id' => $session_id));
			$this->session->set('dynamic_playlist_id', $next['session_id']);
			$this->result = $next['songs'];
		} else {
			throw new Kohana_HTTP_Exception_400('There is no active dynamic playlist for the current user!');
		}
	}


	public function action_index(){
		$artistApi = $this->echonest->getArtistApi();
		$results = $artistApi->search(array('name' => $this->artist_name));
		$artists_count = count($results);
		if($artists_count){
			$artist_id = $this->_get_artist_id($results, $artists_count);
			if($artist_id){
				$artist_info = $artistApi->setId($artist_id)->getProfile('songs');
				$songApi = $this->echonest->getSongApi();
				$songs = array();
				foreach ($artist_info['songs'] as $song){
					$songs[] = $songApi->profile($song['id'], 'song_hotttnesss');
				}
				echo 'songs';
				$this->result = $songs;
			} else {
				echo "No matching artist!";
			}
		} else {
			echo 'artist';
			$this->result = $results;
		}
	}
	
	public function after()
	{
		if($this->result){
			$this->request->headers('Content-Type', 'application/json');
			$this->response->body('<pre>' . json_encode($this->result) . '</pre>');
		}
		
		parent::after();
	}
	
}