//---------------------------- Twitter functions

var twitter = {

	$list: $('#tweet-list'),
	
	init: function() {
		twitter.retweet();
		twitter.favorite();
		twitter.openStatus();
	},
	
	retweet: function() {
		twttr.events.bind('retweet', function(e) {
		    var retweeted_tweet_id = e.data.source_tweet_id,
		    	$retweetBtn = twitter.$list.find('#tweetid-' + retweeted_tweet_id).find('.action-retweet');
		    
		    $retweetBtn.addClass('visitor-retweeted');
		});
	},
	
	favorite: function() {
		twttr.events.bind('favorite', function(event) {
		    var favorited_tweet_id = event.data.tweet_id,
		    	$favoriteBtn = twitter.$list.find('#tweetid-' + favorited_tweet_id).find('.action-favorite');
			
			$favoriteBtn.addClass('visitor-favorited');
		});
	},
	
	openStatus: function() {
		// open the date status link in a popup window
		$('.tweet-date, .in-reply-to').on('click', function(e) {
			e.preventDefault();
			
			window.open(
	            this.href,
	            'tweetStatus',
	            'height=450, width=585, toolbar=0, status=0'
	        );
		});
	}
		
};

//---------------------------- Document Ready

$(document).ready(function() {
	twitter.init();
});