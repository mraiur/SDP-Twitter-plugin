<?php
namespace SDPTwitter{
	class Home{
		private $config;
		public function __construct($config){
			$this->config = $config;
		}

		public function getLastTweets(){
			$obj = new \SDPTwitter\Request($this->config);
			$obj->setURL('https://api.twitter.com/1.1/statuses/user_timeline.json');
			return $obj->doRequest();
		}
	}
}