<?php

define('GD_SOCIALMEDIA_PROVIDER_FACEBOOK', 'facebook');
define('GD_SOCIALMEDIA_PROVIDER_TWITTER', 'twitter');
define('GD_SOCIALMEDIA_PROVIDER_LINKEDIN', 'linkedin');

\PoP\Root\App::getHookManager()->addFilter('gd_socialmedia:providers', 'gdSocialmediaprovidersDefaultproviders');
function gdSocialmediaprovidersDefaultproviders($providers)
{
    $providers[GD_SOCIALMEDIA_PROVIDER_FACEBOOK] = array(
        'shareURL' => 'http://www.facebook.com/share.php?u=%url%&title=%title%',
        'counter-url' => 'https://graph.facebook.com/%s',
        'property' => 'shares',
        'dataType' => 'JSON'
    );
    $providers[GD_SOCIALMEDIA_PROVIDER_TWITTER] = array(
        'shareURL' => 'https://twitter.com/intent/tweet?original_referer=%url%&url=%url%&text=%title%',
        'counter-url' => 'http://urls.api.twitter.com/1/urls/count.json?url=%s',
        'property' => 'count',
        'dataType' => 'JSONP'
    );
    $providers[GD_SOCIALMEDIA_PROVIDER_LINKEDIN] = array(
        'shareURL' => 'http://www.linkedin.com/shareArticle?mini=true&url=%url%',
        'counter-url' => 'http://www.linkedin.com/countserv/count/share?format=jsonp&url=%s',
        'property' => 'count',
        'dataType' => 'JSONP'
    );
    
    return $providers;
}
