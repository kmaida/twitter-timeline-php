//---------------------------- Twitter functions

var twitter = {

	$list: $('#tweet-list'),
	
	init: function() {
		twttr.ready(function(twttr) {
			twitter.retweet();
			twitter.favorite();
			twitter.openStatus();
		});
	},
	
	retweet: function() {
		// add a class to the status li when a visitor successfully retweets a tweet
		twttr.events.bind('retweet', function(e) {
			var retweeted_tweet_id = e.data.source_tweet_id,
				$thisTweet = twitter.$list.find('#tweetid-' + retweeted_tweet_id);
			
			$thisTweet.addClass('visitor-retweeted');
		});
	},
	
	favorite: function() {
		// add a class to the status li when a visitor successfully favorites a tweet
		twttr.events.bind('favorite', function(e) {
			var favorited_tweet_id = e.data.tweet_id,
				$thisTweet = twitter.$list.find('#tweetid-' + favorited_tweet_id);
			
			$thisTweet.addClass('visitor-favorited');
		});
	},
	
	openStatus: function() {
		// open status permalinks in a popup window
		$('.permalink-status').on('click', function(e) {
			var height = 450,
				width = 660,
				top = (screen.height / 2) - (height / 2)
				left = (screen.width / 2) - (width / 2);
			
			e.preventDefault();
			
			window.open(
				this.href,
				'tweetStatus',
				'height=' + height + ', width=' + width + ', toolbar=0, status=0, top=' + top + ', left=' + left
			);
		});
	}
		
};

//---------------------------- Document Ready

$(document).ready(function() {
	twitter.init();
});