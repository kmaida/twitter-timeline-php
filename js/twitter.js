//---------------------------- Twitter functions

var twitter = {

	$list: $('#tweet-list'),
	
	init: function() {
		twitter.retweet();
		twitter.favorite();
		twitter.openStatus();
	},
	
	retweet: function() {
		// add a class to the Retweet link when a visitor successfully retweets a tweet
		twttr.events.bind('retweet', function(e) {
			var retweeted_tweet_id = e.data.source_tweet_id,
				$retweetBtn = twitter.$list.find('#tweetid-' + retweeted_tweet_id).find('.action-retweet');
			
			$retweetBtn.addClass('visitor-retweeted');
		});
	},
	
	favorite: function() {
		// add a class to the Favorite link when a visitor successfully favorites a tweet
		twttr.events.bind('favorite', function(event) {
			var favorited_tweet_id = event.data.tweet_id,
				$favoriteBtn = twitter.$list.find('#tweetid-' + favorited_tweet_id).find('.action-favorite');
			
			$favoriteBtn.addClass('visitor-favorited');
		});
	},
	
	openStatus: function() {
		// open status permalinks in a popup window
		$('.tweet-date, .in-reply-to').on('click', function(e) {
			var height = 450,
				width = 585,
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