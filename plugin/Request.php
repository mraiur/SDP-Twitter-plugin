<?php
namespace SDPTwitter{
	class Request{
		private $config;
		private $url;

		public function __construct($config){

			$this->config = $config;
		}


		private function oauthHeader(){

		}

		public function setURL($url){
			$this->url = $url;
		}

		private function getOauthHash(){

			$oauth_hash = '';
			$oauth_hash .= 'oauth_consumer_key='.$this->config['consumer_key'].'&';
			$oauth_hash .= 'oauth_nonce=' . time() . '&';
			$oauth_hash .= 'oauth_signature_method=HMAC-SHA1&';
			$oauth_hash .= 'oauth_timestamp=' . time() . '&';
			$oauth_hash .= 'oauth_token='.$this->config['token'].'&';
			$oauth_hash .= 'oauth_version='.$this->config['version'];

			return $oauth_hash;
		}

		private function getOauthHeader($signature){
			$oauth_header = '';
			$oauth_header .= 'oauth_consumer_key="'.$this->config['consumer_key'].'", ';
			$oauth_header .= 'oauth_nonce="' . time() . '", ';
			$oauth_header .= 'oauth_signature="' . $signature . '", ';
			$oauth_header .= 'oauth_signature_method="HMAC-SHA1", ';
			$oauth_header .= 'oauth_timestamp="' . time() . '", ';
			$oauth_header .= 'oauth_token="'.$this->config['token'].'", ';
			$oauth_header .= 'oauth_version="'.$this->config['version'].'", ';

			return array("Authorization: Oauth {$oauth_header}", 'Expect:');
		}

		public function doRequest(){
			
			$oauth_hash =  $this->getOauthHash();

			$base = '';
			$base .= 'GET';
			$base .= '&';
			$base .= rawurlencode($this->url);
			$base .= '&';
			$base .= rawurlencode($oauth_hash);

			$key = '';
			$key .= rawurlencode($this->config['consumer_key_secret']);
			$key .= '&';
			$key .= rawurlencode($this->config['token_secret']);

			$signature = base64_encode(hash_hmac('sha1', $base, $key, true));
			$signature = rawurlencode($signature);

			$curl_header = $this->getOauthHeader($signature);		


			$curl_request = curl_init();
			curl_setopt($curl_request, CURLOPT_HTTPHEADER, $curl_header);
			curl_setopt($curl_request, CURLOPT_HEADER, false);
			curl_setopt($curl_request, CURLOPT_URL, $this->url );
			curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, false);
			$json = curl_exec($curl_request);
			curl_close($curl_request);

			return $json;
		}
	}
}