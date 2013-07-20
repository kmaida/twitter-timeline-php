twitter-timeline-php
====================

###What?

A small framework to get and display an interactive Twitter timeline, implemented with the Twitter API v1.1, PHP, a little JavaScript, and web intents.

###Why?

Because Twitter did away with that whole blogger.js business that enabled web designers and developers to easily display a few recent tweets on their websites in whatever presentation they wished.

Version 1.1 of the Twitter API has stricter branding and guidelines for displaying [Twitter on websites](https://dev.twitter.com/docs/twitter-for-websites). Displaying a timeline or tweet on your site qualifies you as having created a Twitter application, which means that you are expected to adhere to the [Developer Display Requirements](https://dev.twitter.com/terms/display-requirements). 

It now takes a significant amount of effort to get a styled / customized timeline on your own site. Wading through OAuth and parsing and formatting large amounts of JSON aren't on some peoples' to-do lists when all they want is a couple of tweets to appear on their homepage. 

Some creatives bite the bullet and accept the embedded tweets and timeline widgets that Twitter supplies. Others aren't so happy about not being able to display their tweets cleverly and creatively, and they start learning about the Twitter API-- when they'd never set out to *build a Twitter application* in the first place.

###How?

This framework exists to lower that barrier to entry. It's not blogger.js-simple anymore, but it's a place to start.

## Features

* OAuth with GET requests
* Base markup that adheres to the [Developer Display Requirements](https://dev.twitter.com/terms/display-requirements)
* Reply / Retweet / Favorite via [web intents](https://dev.twitter.com/docs/intents)
* Formatting of timestamp
* Formatting of tweet text (linking links, hashtags, mentions)
* Detection of whether a tweet is a retweet or reply and appropriate added functionality
* Dynamic application of CSS classes when a visitor retweets or favorites

##How to Use

*Coming soon*