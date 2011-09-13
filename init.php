<?php defined('SYSPATH') or die('No direct access allowed!'); 

Route::set('echonest_dynamic_playlist', 'echonest/dynamic/<artist>')
		->defaults(array(
			'controller' => 'echonest',
			'action' => 'dynamic',
			'artist' => false
		));

Route::set('echonest_dynamic_playlist_next', 'echonest/dynamic/next')
		->defaults(array(
			'controller' => 'echonest',
			'action' => 'dynamic_next'
		));

Route::set('echonest_dynamic_playlist_rate', 'echonest/dynamic/rate/<rating>', array('rating' => '[1-5]'))
		->defaults(array(
			'controller' => 'echonest',
			'action' => 'dynamic_rate',
			'rating' => 5
		));

Route::set('echonest_dynamic_playlist_tempo', 'echonest/dynamic/tempo/<tempo>', array('tempo' => '([1-9][0-9]?|100)'))
		->defaults(array(
			'controller' => 'echonest',
			'action' => 'dynamic_tempo',
			'tempo' => '50'
		));