<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter('excerpt_length', function($length) {
	return 300;
});

// Remove shortcodes from the excerpt
HooksAPIFacade::getInstance()->addFilter('the_excerpt', 'strip_shortcodes', 0);
HooksAPIFacade::getInstance()->addFilter('the_excerpt', 'make_clickable');
HooksAPIFacade::getInstance()->removeFilter('the_excerpt', 'wpautop');
HooksAPIFacade::getInstance()->addFilter('get_the_excerpt', 'strip_shortcodes', 0);
HooksAPIFacade::getInstance()->addFilter('get_the_excerpt', 'make_clickable');
HooksAPIFacade::getInstance()->addFilter('the_content', 'make_clickable');

// Remove empty whitspaces at the end of the article. Eg: when adding a Youtube video at the end, we gotta have the Youtube URL
// in 1 line so must add a break after it, but when it's the last thing in the article, at adds an ugly whitespace
// Do it before adding filter 'wpautop'
// Taken from https://secure.php.net/manual/en/function.trim.php
HooksAPIFacade::getInstance()->addFilter('the_content', 'trim', 1);

function gdGetWebsiteDescription($addhomelink = true)
{
    $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
    return HooksAPIFacade::getInstance()->applyFilters('gdGetWebsiteDescription', $cmsapplicationapi->getSiteDescription(), $addhomelink);
}
