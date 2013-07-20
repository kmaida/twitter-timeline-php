<!doctype HTML>
<html lang="en">
<head>
	<title>Twitter Timeline with API 1.1</title>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="robots" content="index,follow">
	<link rel="stylesheet" href="css/styles.css">
</head>

<body>

	<h1>Twitter Timeline with API 1.1</h1>
	
	<p>The default markup outputted by this framework adheres to the <a href="https://dev.twitter.com/terms/display-requirements">Twitter developer display requirements</a>. Feel free to modify it as needed, but Twitter has made it clear that they would appreciate if developers played by their rules.</p>
	
	<p><strong>Branding</strong>: <a class="twitter-branding" href="https://dev.twitter.com/docs/image-resources">Twitter logo</a> linking back to Twitter -or- <a href="https://dev.twitter.com/docs/follow-button">official Follow button</a> should be present.</p>
	
	<?php include_once('_includes/twitter.php'); ?>
	
	<footer>
		<a href="https://github.com/kmaida/twitter-timeline-php">twitter-timeline-php</a> on <a href="http://github.com">GitHub</a><br>
		GNU Public License
	</footer>
	
	<!-- jQuery library -->
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
	<!-- Optional: include //code.jquery.com/jquery-migrate-1.2.1.js if IE6/7/8 support is needed -->
	
	<!-- Web Intents for Reply / Retweet / Favorite popup functionality (https://dev.twitter.com/docs/intents) -->
	<script type="text/javascript" src="//platform.twitter.com/widgets.js"></script>
	<!-- Custom Twitter functions -->
	<script type="text/javascript" src="js/twitter.js"></script>
</body>
</html>