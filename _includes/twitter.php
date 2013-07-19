<?php

/**
 * twitter-timeline-php : Twitter API 1.1 user timeline implemented with PHP, a little JavaScript, and web intents
 * 
 * @package  twitter-timeline-php
 * @author   Kim Maida <contact@kim-maida.com>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://github.com/kmaida/twitter-timeline-php
 *
**/
	
############################################################### 
	## SETTINGS
	
	// Set access tokens <https://dev.twitter.com/apps/>
	$settings = array(
	    'oauth_access_token' => "",
	    'oauth_access_token_secret' => "",
	    'consumer_key' => "",
	    'consumer_secret' => ""
	);
	
	// Set API request URL and timeline variables if needed <https://dev.twitter.com/docs/api/1.1>
	$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
	$twitterUsername = "";
	$tweetCount = 3;
	
	// Use private tokens for development if they exist; delete when no longer necessary
	$tokens = '_utils/tokens.php';
	is_file($tokens) AND include $tokens;
	
	require_once('_utils/twitter-api-oauth.php');
	
###############################################################
	## MAKE GET REQUEST
	
	$getfield = '?screen_name=' . $twitterUsername . '&count=' . $tweetCount;
	$twitter = new TwitterAPITimeline($settings);
	
	$json = $twitter->setGetfield($getfield)	// Note: Set the GET field BEFORE calling buildOauth()
	             	->buildOauth($url, $requestMethod)
				 	->performRequest();
				 			
	$twitter_data = json_decode($json, true);	// Create an array with the fetched JSON data
	
############################################################### 	
	## DO SOMETHING WITH THE DATA
	
	function formatTweet($tweet) {
		$linkified = '@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@';
		$hashified = '/(^|[\n\s])#([^\s"\t\n\r<:]*)/is';
		$mentionified = '/(^|[\n\s])@([^\s"\t\n\r<:]*)/is';
		
		$prettyTweet = preg_replace(
					array(
						$linkified,
						$hashified,
						$mentionified
					), 
					array(
						'<a href="$1" class="link-tweet" target="_blank">$1</a>',
						'$1<a class="link-hash" href="https://twitter.com/search?q=%23$2&src=hash">#$2</a>',
						'$1<a href="link-mention" href="http://twitter.com/$2">@$2</a>'
					), 
					$tweet
				);
		
		return $prettyTweet;
	}
	
	function formatDate($dateStr) {
		date_default_timezone_set('America/Detroit');
		$date = date('d M y', strtotime($dateStr));
		return $date;
	}
	
	echo '<ul id="tweet-list" class="tweet-list">';
	
	// The Loop
	foreach ($twitter_data as $tweet) {
	
		$retweet = $tweet['retweeted_status'];
		$isRetweet = !empty($retweet);
		
		// User
		$user = !$isRetweet ? $tweet['user'] : $retweet['user'];
		$userName = $user['name'];
		$userScreenName = $user['screen_name'];
		$userAvatarURL = stripcslashes($user['profile_image_url']);
		$userAccountURL = 'http://twitter.com/' . $userScreenName;
		
		// The tweet
		$id = $tweet['id'];
		$formattedTweet = !$isRetweet ? formatTweet($tweet['text']) : formatTweet($retweet['text']);
		$statusURL = 'http://twitter.com/' . $userScreenName . '/status/' . $id;
		$date = formatDate($tweet['created_at']);
		
		// Reply
		$replyID = $tweet['in_reply_to_status_id'];
		$isReply = !empty($replyID);

		// Actions
		$replyURL = 'https://twitter.com/intent/tweet?in_reply_to=' . $id;
		$retweetURL = 'https://twitter.com/intent/retweet?tweet_id=' . $id;
		$favoriteURL = 'https://twitter.com/intent/favorite?tweet_id=' . $id;
		
?>
			
			<li id="<?php echo 'tweetid-' . $id; ?>" class="tweet<?php if ($isRetweet) echo ' is-retweet'; if ($isReply) echo ' is-reply'; ?>">
				<div class="tweet-info">
					<div class="user-info">
						<img class="user-avatar" src="<?php echo $userAvatarURL; ?>">
						<p class="user-account">
							<strong class="user-name"><?php echo $userName; ?></strong>
							<a class="user-screenName" href="<?php echo $userAccountURL; ?>">@<?php echo $userScreenName; ?></a>
						</p>
					</div>
					<a class="tweet-date" href="<?php echo $statusURL; ?>" target="_blank">
						<?php echo $date; ?>
					</a>
				</div>
				<blockquote class="tweet-text">
					<?php echo $formattedTweet; ?>
					<?php if ($isReply) echo '<a class="in-reply-to" href="http://twitter.com/' . $tweet['in_reply_to_screen_name'] . '/status/' . $replyID . '"><em>In reply to...</em></a>'; ?>
				</blockquote>
				<div class="tweet-actions">
					<a class="action-reply" href="<?php echo $replyURL; ?>">Reply</a>
					<a class="action-retweet<?php if ($tweet['retweeted']) echo ' visitor-retweeted'; ?>" href="<?php echo $retweetURL; ?>">Retweet</a>
					<a class="action-favorite<?php if ($tweet['favorited']) echo ' visitor-favorited'; ?>" href="<?php echo $favoriteURL; ?>">Favorite</a>
				</div>
			</li>
			
<?php }
	
	echo '</ul>';
	
	// echo $json;
?>