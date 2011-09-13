<?php defined('SYSPATH') or die('No direct access allowed!'); 

class Echonest extends EchoNest_Client {
	
	public function __construct(EchoNest_HttpClientInterface $httpClient = null)
	{
		parent::__construct($httpClient);
	}
	
	public static function instance(EchoNest_HttpClientInterface $httpClient = null){
		$echonest = new Echonest($httpClient);
		$api_key = Kohana::$config->load('echonest')->get('api_key');
		if(empty($api_key)){
			throw new Kohana_Exception("No api key specified in the config file! Copy the " . MODPATH . "echonest/config/echonest.php to " . APPPATH . "config/echonest.php and enter the api key. If you don\'t have an api key request one from http://developer.echonest.com/account/register ");
		}
		$echonest->authenticate($api_key);
		return $echonest;
	}
}