<?php defined('SYSPATH') or die('No direct access allowed!');

class Controller_Echonest_Playlist extends Controller_Echonest{
	
	public function action_dynamic(){
		$artist = str_replace('_', ' ', $this->request->param('artist', $this->artist_name));
		if($artist){
			$playlist_api = $this->echonest->getPlaylistApi();
			$dynamic_playlist = $playlist_api->getDynamic(array('artist' => $artist, 'type' => 'artist-radio'));
			$this->session->set('dynamic_playlist_id', $dynamic_playlist['session_id']);
			$this->template->result = $dynamic_playlist['songs'];
		}
	}
	
	public function action_dynamic_rate(){
		$rating = $this->request->param('rating', 5);
		if(($session_id = $this->session->get('dynamic_playlist_id'))){
			$playlist_api = $this->echonest->getPlaylistApi();
			$next = $playlist_api->getDynamic(array('session_id' => $session_id, 'rating' => $rating));
			$this->session->set('dynamic_playlist_id', $next['session_id']);
			$this->template->result = $next['songs'];
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
			$this->template->result = $next['songs'];
		} else {
			throw new Kohana_HTTP_Exception_400('There is no active dynamic playlist for the current user!');
		}
	}
	
	public function action_dynamic_mood(){
		$mood = $this->request->param('mood');
		$moods = $this->_get_terms('mood');
		$moods_count = count($moods);
		$i = 0;
		$mood_ok = false;
		while($i < $moods_count && !$mood_ok)
		{
			if($mood == $moods[$i]['name'])
			{
				$mood_ok = true;
			}
			$i = $i + 1;
		}
		if($mood_ok)
		{
			if(($session_id = $this->session->get('dynamic_playlist_id')))
			{
				$playlist_api = $this->echonest->getPlaylistApi();
				$next = $playlist_api->getDynamic(array('session_id' => $session_id, 'steer_mood' =>  $mood . '^1.5'));
				$this->session->set('dynamic_playlist_id', $next['session_id']);
				$this->template->result = $next['songs'];
			} 
			else 
			{
				throw new Kohana_HTTP_Exception_400('There is no active dynamic playlist for the current user!');
			}
		}
		else
		{
			throw new Kohana_HTTP_Exception_400('There is no such mood!');
		}
	}
	
	public function action_dynamic_next(){
		if(($session_id = $this->session->get('dynamic_playlist_id'))){
			$playlist_api = $this->echonest->getPlaylistApi();
			$next = $playlist_api->getDynamic(array('session_id' => $session_id));
			$this->session->set('dynamic_playlist_id', $next['session_id']);
			$this->template->result = $next['songs'];
		} else {
			throw new Kohana_HTTP_Exception_400('There is no active dynamic playlist for the current user!');
		}
	}
}