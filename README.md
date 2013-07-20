twitter-timeline-php
====================

A small framework to get and display an interactive Twitter timeline, implemented with the Twitter API v1.1, PHP, a little JavaScript, and web intents.

##Introduction

A while ago, Twitter did away with that whole *blogger.js* business that enabled web designers and developers to easily display a few recent tweets on their websites in whatever presentation they wished.

[Version 1.1 of the Twitter API](https://dev.twitter.com/docs/api/1.1) has stricter branding guidelines for displaying [Twitter on websites](https://dev.twitter.com/docs/twitter-for-websites). Using the API to add a timeline or tweet on your site qualifies you as having created a Twitter application, which means you're expected to adhere to the [Developer Display Requirements](https://dev.twitter.com/terms/display-requirements). 

It now takes a significant amount of effort to get a styled / customized timeline on your own site. Wading through OAuth and parsing heaps of JSON data aren't on some peoples' to-do lists when all they want is a couple of tweets to appear on their homepage. 

Many creatives bite the bullet and accept the embedded tweets and timeline widgets that Twitter supplies. Others aren't so willing to surrender the ability to style tweets creatively, and they start learning about the Twitter API.

This framework exists to lower that barrier to entry. It's not *blogger.js*-simple anymore, but it's a place to start.

## Features

* OAuth with GET request to fetch API 1.1 URL (user_timeline by default)
* Basic HTML markup that adheres to the [Developer Display Requirements](https://dev.twitter.com/terms/display-requirements)
* Reply / Retweet / Favorite via [web intents](https://dev.twitter.com/docs/intents)
* Format shortform timestamp (h, m, s elapsed, date if 24+ hours ago)
* Format tweet text (linking links, hashtags, mentions)
* Detection if a status is a retweet or reply
* JavaScript event handlers bound for successful visitor retweets and favorites
* Status permalinks open in popup dialog like Twitter web intents
* Fully customizable HTML output
* Fully styleable with CSS

## Requirements

* Apache / PHP
* cURL enabled

##Demo

Here is a simple [demo Twitter Timeline](http://dev.kim-maida.com/twitter). The demo is identical to the files contained in this repository. A few very basic styles have been included to follow the [Developer Display Requirements](https://dev.twitter.com/terms/display-requirements).

##How to Use

###Create a Twitter app

Sign into [Twitter Developers](https://dev.twitter.com/apps) and click the **"Create a new application"** button.

Fill in your application's name, description, and website. Agree to the TOS and proceed to the next step.

Click the **"Create my access token"** button.

Once the token has been generated, the OAuth tab should list your **Consumer key**, **Consumer secret**, **Access token**, and **Access token secret**.

###Add twitter-api-oauth.php to your site

There is no need to modify this file if you are using GET requests, just make sure it is included and that **twitter.php** references its path properly.

###Add twitter.php to your site

Include the **twitter.php** file where you'd like your timeline to show up in your site.

	<?php include_once('path_to/twitter.php'); ?>

Edit the **twitter.php** *Settings* section to define your Twitter app's **Consumer key**, **Consumer secret**, **Access token**, **Access token secret**.

	$settings = array(
		'consumer_key' => "",
		'consumer_secret' => "",
		'oauth_access_token' => "",
		'oauth_access_token_secret' => ""
	);

The default [API URL](https://dev.twitter.com/docs/api/1.1) is the user timeline. (Changing this will require the loop to be modified for whatever other JSON you'd like to use.)

Set your **Twitter username** and **tweet count** to display that number of tweets in your timeline.

	$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
	$twitterUsername = "";
	$tweetCount = 3;

Make sure that the path to the OAuth class is correct for your site's setup:

	require_once('path_to/twitter-api-oauth.php');
	
The last section of **twitter.php** is the loop that displays each timeline status. By default, it obeys the [Developer Display Requirements](https://dev.twitter.com/terms/display-requirements) laid out by Twitter. Modify the formatting functions and loop to fit your needs.

###Add the JavaScript to your site

Include JavaScript to support reply/retweet/favorite links opening in popup dialog boxes and event handlers for successful interactions.

	<script type="text/javascript" src="//platform.twitter.com/widgets.js"></script>
	<script type="text/javascript" src="PATH_TO/jquery.js"></script>
	<script type="text/javascript" src="PATH_TO/twitter.js"></script>
	
###Add CSS (optional)

The CSS is very basic. Include it if you'd like a small starter for adding your own styles.