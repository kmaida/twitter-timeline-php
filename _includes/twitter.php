<?php

/**
 * twitter-timeline-php : Twitter API 1.1 user timeline implemented with PHP, a little JavaScript, and web intents
 * 
 * @package  twitter-timeline-php
 * @author   Kim Maida <contact@kim-maida.com>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://github.com/kmaida/twitter-timeline-php
 * @credits	 Thank you to <http://viralpatel.net/blogs/twitter-like-n-min-sec-ago-timestamp-in-php-mysql/> for the kickstart on the "time ago" calculations 
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
	
//-------------------------------------------------------------- Format the time(ago) and date of each tweet
	
	function timeAgo($dateStr) {
		$timestamp = strtotime($dateStr);	 
		$day = 60 * 60 * 24;
	    $today = time(); /* current unix time  */
	    $since = $today - $timestamp;
	    
	    // If it's been less than 1 day since the tweet was posted, figure out how long ago in seconds/minutes/hours
	    if (($since / $day) < 1) {
	    
	    	$timeUnits = array(
			    array(60 * 60, 'h'),
			    array(60, 'm'),
			    array(1, 's')
		    );
		    
		    for ($i = 0, $j = count($timeUnits); $i < $j; $i++) { 
			    $seconds = $timeUnits[$i][0];
			    $name = $timeUnits[$i][1];
			 
			    if (($count = floor($since / $seconds)) != 0) {
			        break;
			    }
		    }
		 
		    $print = "$count{$name}";
		 
		    if ($i + 1 < $j) {
			    $seconds2 = $timeUnits[$i + 1][0];
			    $name2 = $timeUnits[$i + 1][1];
			 
			    if (($count2 = floor(($since - ($seconds * $count)) / $seconds2)) != 0) {
			        $print .= " $count2{$name2}";
			    }
		    }
		    return $print;
		    
		// If it's been a day or more, return the date
	    } else {
	    	# day (without leading 0) and 3-letter month
		    return date('j M', strtotime($dateStr));
	    }   
	}
	
//-------------------------------------------------------------- Format the tweet text (links, hashtags, mentions)
	
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
	
//-------------------------------------------------------------- Begin HTML output
	
	echo '<ul id="tweet-list" class="tweet-list">';
	
	foreach ($twitter_data as $tweet) {
	
		$retweet = $tweet['retweeted_status'];
		$isRetweet = !empty($retweet);
		
		# Retweet - get the retweeter's name and screen name
		$retweetingUser = $isRetweet ? $tweet['user']['name'] : null;
		$retweetingUserScreenName = $isRetweet ? $tweet['user']['screen_name'] : null;
		
		# Tweet source user (could be a retweeted user and not the owner of the timeline)
		$user = !$isRetweet ? $tweet['user'] : $retweet['user'];	
		$userName = $user['name'];
		$userScreenName = $user['screen_name'];
		$userAvatarURL = stripcslashes($user['profile_image_url']);
		$userAccountURL = 'http://twitter.com/' . $userScreenName;
		
		# The tweet
		$id = $tweet['id'];
		$formattedTweet = !$isRetweet ? formatTweet($tweet['text']) : formatTweet($retweet['text']);
		$statusURL = 'http://twitter.com/' . $userScreenName . '/status/' . $id;
		$date = timeAgo($tweet['created_at']);
		
		# Reply
		$replyID = $tweet['in_reply_to_status_id'];
		$isReply = !empty($replyID);

		# Actions (uses web intents)
		$replyURL = 'https://twitter.com/intent/tweet?in_reply_to=' . $id;
		$retweetURL = 'https://twitter.com/intent/retweet?tweet_id=' . $id;
		$favoriteURL = 'https://twitter.com/intent/favorite?tweet_id=' . $id;
		
?>
			<!-- This output markup adheres to the Twitter developer display requirements (https://dev.twitter.com/terms/display-requirements) -->
			
			<li id="<?php echo 'tweetid-' . $id; ?>" class="tweet<?php if ($isRetweet) echo ' is-retweet'; if ($isReply) echo ' is-reply'; ?>">
				<div class="tweet-info">
					<div class="user-info">
						<a class="user-avatar-link" href="<?php echo $userAccountURL; ?>">
							<img class="user-avatar" src="<?php echo $userAvatarURL; ?>">
						</a>
						<p class="user-account">
							<a class="user-name" href="<?php echo $userAccountURL; ?>"><strong><?php echo $userName; ?></strong></a>
							<a class="user-screenName" href="<?php echo $userAccountURL; ?>">@<?php echo $userScreenName; ?></a>
						</p>
					</a>
					<a class="tweet-date" href="<?php echo $statusURL; ?>" target="_blank">
						<?php echo $date; ?>
					</a>
				</div>
				<blockquote class="tweet-text">
					<?php 	
						echo $formattedTweet; 
					 
						if ($isReply) {
							echo '
								<a class="in-reply-to" href="http://twitter.com/' . $tweet['in_reply_to_screen_name'] . '/status/' . $replyID . '">
									<em>In reply to...</em>
								</a>
							';
						}
						
						if ($isRetweet) {
							echo '
								<span class="retweeter">
									Retweeted by <a class="retweeter-link" href="http://twitter.com/' . $retweetingUserScreenName . '">'
									. $retweetingUser .
									'</a>
								</span>
							';
						} 
					?>	
				
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