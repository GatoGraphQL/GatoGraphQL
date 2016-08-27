<?php

define ('GD_SOCIALMEDIA_PROVIDER_FACEBOOK', 'facebook');
define ('GD_SOCIALMEDIA_PROVIDER_TWITTER', 'twitter');
define ('GD_SOCIALMEDIA_PROVIDER_LINKEDIN', 'linkedin');

/**---------------------------------------------------------------------------------------------------------------
 *
 * Social Media
 *
 * ---------------------------------------------------------------------------------------------------------------*/

function gd_twitter_user() {

	return apply_filters('gd_twitter_user', '');
}

function gd_socialmedia_provider_settings() {

	return apply_filters('gd_socialmedia:providers', array());
}

add_filter('gd_jquery_constants', 'gd_jquery_constants_socialmedia');
function gd_jquery_constants_socialmedia($jquery_constants) {

	$jquery_constants['SOCIALMEDIA'] = gd_socialmedia_provider_settings();	
	return $jquery_constants;
}

add_filter('gd_socialmedia:providers', 'gd_socialmedia_defaultproviders');
function gd_socialmedia_defaultproviders($providers) {

	$providers[GD_SOCIALMEDIA_PROVIDER_FACEBOOK] = array(
		'share-url' => 'http://www.facebook.com/share.php?u=%url%&title=%title%',
		'counter-url' => 'https://graph.facebook.com/%s',
		'property' => 'shares',
		'dataType' => 'JSON'
	);
	$providers[GD_SOCIALMEDIA_PROVIDER_TWITTER] = array(
		'share-url' => 'https://twitter.com/intent/tweet?original_referer=%url%&url=%url%&text=%title%',
		'counter-url' => 'http://urls.api.twitter.com/1/urls/count.json?url=%s',
		'property' => 'count',
		'dataType' => 'JSONP'
	);
	$providers[GD_SOCIALMEDIA_PROVIDER_LINKEDIN] = array(
		'share-url' => 'http://www.linkedin.com/shareArticle?mini=true&url=%url%',
		'counter-url' => 'http://www.linkedin.com/countserv/count/share?format=jsonp&url=%s',
		'property' => 'count',
		'dataType' => 'JSONP'
	);
	
	return $providers;
}