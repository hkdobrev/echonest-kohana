<?php defined('SYSPATH') or die('No direct access allowed!'); 

Route::set('echonest_search_artist', 'echonest/search/<artist>', array('artist' => '[a-z-_]+'))
		->defaults(array(
			'directory' => 'echonest',
			'controller' => 'search',
			'action' => 'search_by_name'
		));

Route::set('echonest_dynamic_playlist', 'echonest/playlist/<artist>')
		->defaults(array(
			'directory' => 'echonest',
			'controller' => 'playlist',
			'action' => 'dynamic',
			'artist' => false
		));

Route::set('echonest_dynamic_playlist_next', 'echonest/playlist/next')
		->defaults(array(
			'directory' => 'echonest',
			'controller' => 'playlist',
			'action' => 'dynamic_next'
		));

Route::set('echonest_dynamic_playlist_rate', 'echonest/playlist/rate/<rating>', array('rating' => '[1-5]'))
		->defaults(array(
			'directory' => 'echonest',
			'controller' => 'playlist',
			'action' => 'dynamic_rate',
			'rating' => 5
		));

Route::set('echonest_dynamic_playlist_tempo', 'echonest/playlist/tempo/<tempo>', array('tempo' => '([1-9][0-9]?|100)'))
		->defaults(array(
			'directory' => 'echonest',
			'controller' => 'playlist',
			'action' => 'dynamic_tempo',
			'tempo' => '50'
		));

Route::set('echonest_artist_terms', 'echonest/<type>', array('type' => '(moods|styles)'))
		->defaults(array(
			'directory' => 'echonest',
			'controller' => 'playlist',
			'action' => 'terms'
		));

Route::set('echonest_dynamic_mood', 'echonest/playlist/mood/<mood>', array('mood' => '[a-z-]+'))
		->defaults(array(
			'directory' => 'echonest',
			'controller' => 'playlist',
			'action' => 'dynamic_mood'
		));