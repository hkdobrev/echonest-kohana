<?php defined('SYSPATH') or die('No direct access allowed!');

class Controller_Echonest extends Controller_Template{
	
	public function before()
	{
		parent::before();
		$this->template->result = '';
		$this->echonest = Echonest::instance();
		$this->session = Session::instance();
	}
	
	protected function _get_artist_id($artists, $artists_count, $artist_name){
		$artist_id = false;
		$i = -1;
		while($i < $artists_count - 1 && !$artist_id)
		{
			$i = $i + 1;
			$artist = $artists[$i];
			if(trim(strtolower($artist_name)) === trim(strtolower($artist['name']))){
				$artist_id = $artist['id'];
			}
		}
		return $artist_id;
	}
	
	protected function _get_terms($type){
		$terms = $this->echonest->get('artist/list_terms', array('type' => $type));
		return $terms['terms'];
	}
	
	public function action_terms(){
		$type = $this->request->param('type');
		if($type == 'styles'){
			$type = 'style';
		} else{
			$type = 'mood';
		}
		$terms = $this->_get_terms($type);
		$this->template->result = $terms;
	}
}