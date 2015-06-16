<?php
require_once "plugin.php";
$config = require_once "../SDP/config.php";

$home = new \SDPTwitter\Home($config['TWITTER_PLUGIN']);
$tweets = $home->getLastTweets();
var_dump($tweets);
