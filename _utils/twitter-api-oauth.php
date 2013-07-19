<?php

/**
 * twitter-timeline-php : Twitter API 1.1 user timeline implemented with PHP, a little JavaScript, and web intents
 * 
 * @package  twitter-timeline-php
 * @author   Kim Maida <contact@kim-maida.com>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://github.com/kmaida/twitter-timeline-php
 * @credits	 Based on code from Rivers <http://stackoverflow.com/a/12939923> and James Mallison <https://github.com/J7mbo/twitter-api-php>
 *
**/

if (class_exists('TwitterAPITimeline') === false) {
	class TwitterAPITimeline
	{
		private $oauth_access_token;
	    private $oauth_access_token_secret;
	    private $consumer_key;
	    private $consumer_secret;
	    private $postfields;
	    private $getfield;
	    protected $oauth;
	    public $url;
	    
	    public function __construct(array $settings)
	    {
	        if (!in_array('curl', get_loaded_extensions())) 
	        {
	            throw new Exception('You need to install cURL, see: http://curl.haxx.se/docs/install.html');
	        }
	        
	        if (!isset($settings['oauth_access_token'])
	            || !isset($settings['oauth_access_token_secret'])
	            || !isset($settings['consumer_key'])
	            || !isset($settings['consumer_secret']))
	        {
	            throw new Exception('Make sure you are passing in the correct parameters');
	        }
	
	        $this->oauth_access_token = $settings['oauth_access_token'];
	        $this->oauth_access_token_secret = $settings['oauth_access_token_secret'];
	        $this->consumer_key = $settings['consumer_key'];
	        $this->consumer_secret = $settings['consumer_secret'];
	    }
	    
	    public function setGetfield($string)
	    {
	        $search = array('#', ',', '+', ':');
	        $replace = array('%23', '%2C', '%2B', '%3A');
	        $string = str_replace($search, $replace, $string);  
	        
	        $this->getfield = $string;
	        
	        return $this;
	    }
	    
	    public function getGetfield()
	    {
	        return $this->getfield;
	    }
	
		private function buildBaseString($baseURI, $method, $params) 
	    {
	        $return = array();
	        ksort($params);
	        
	        foreach($params as $key=>$value)
	        {
	            $return[] = "$key=" . $value;
	        }
	        
	        return $method . "&" . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $return)); 
	    }
	    
	    private function buildAuthorizationHeader($oauth) 
	    {
	        $return = 'Authorization: OAuth ';
	        $values = array();
	        
	        foreach($oauth as $key => $value)
	        {
	            $values[] = "$key=\"" . rawurlencode($value) . "\"";
	        }
	        
	        $return .= implode(', ', $values);
	        return $return;
	    }
	    
	    public function buildOauth($url)
		{	 
	        $consumer_key = $this->consumer_key;
	        $consumer_secret = $this->consumer_secret;
	        $oauth_access_token = $this->oauth_access_token;
	        $oauth_access_token_secret = $this->oauth_access_token_secret;
	        
	        $oauth = array( 
	            'oauth_consumer_key' => $consumer_key,
	            'oauth_nonce' => time(),
	            'oauth_signature_method' => 'HMAC-SHA1',
	            'oauth_token' => $oauth_access_token,
	            'oauth_timestamp' => time(),
	            'oauth_version' => '1.0'
	        );
	        
	        $getfield = $this->getGetfield();
	        
	        if (!is_null($getfield))
	        {
	            $getfields = str_replace('?', '', explode('&', $getfield));
	            foreach ($getfields as $g)
	            {
	                $split = explode('=', $g);
	                $oauth[$split[0]] = $split[1];
	            }
	        }
	        
	        $base_info = $this->buildBaseString($url, 'GET', $oauth);
	        $composite_key = rawurlencode($consumer_secret) . '&' . rawurlencode($oauth_access_token_secret);
	        $oauth_signature = base64_encode(hash_hmac('sha1', $base_info, $composite_key, true));
	        $oauth['oauth_signature'] = $oauth_signature;
	        
	        $this->url = $url;
	        $this->oauth = $oauth;
	        
	        return $this;
	    }
		
	    public function performRequest($return = true)
	    {
	        if (!is_bool($return)) 
	        { 
	            throw new Exception('performRequest parameter must be true or false'); 
	        }
	        
	        $header = array($this->buildAuthorizationHeader($this->oauth), 'Expect:');
	        
	        $getfield = $this->getGetfield();
	
	        $options = array( 
	            CURLOPT_HTTPHEADER => $header,
	            CURLOPT_HEADER => false,
	            CURLOPT_URL => $this->url,
	            CURLOPT_RETURNTRANSFER => true,
	            CURLOPT_SSL_VERIFYPEER => false
	        );
	
	        if ($getfield !== '')
	        {
	            $options[CURLOPT_URL] .= $getfield;
	        }
	
	        $feed = curl_init();
	        curl_setopt_array($feed, $options);
	        $json = curl_exec($feed);
	        curl_close($feed);
	
	        if ($return) { return $json; }
	    }
		
	}
}
?>