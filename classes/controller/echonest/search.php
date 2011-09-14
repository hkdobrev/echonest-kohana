<?php defined('SYSPATH') or die('No direct access allowed!');

class Controller_Echonest_Search extends Controller_Echonest {
	
	public function action_search_by_name(){
		$artist_name = $this->request->param('artist');
		$artistApi = $this->echonest->getArtistApi();
		$results = $artistApi->search(array('name' => $artist_name));
		$artists_count = count($results);
		if($artists_count){
			$artist_id = $this->_get_artist_id($results, $artists_count, $artist_name);
			if($artist_id){
				$artist_info = $artistApi->setId($artist_id)->getProfile('songs');
				$songApi = $this->echonest->getSongApi();
				$songs = array();
				foreach ($artist_info['songs'] as $song){
					$songs[] = $songApi->profile($song['id'], 'song_hotttnesss');
				}
				echo 'songs';
				$this->template->result = $songs;
			} else {
				echo "No matching artist!";
			}
		} else {
			echo 'artist';
			$this->template->result = $results;
		}
	}
}