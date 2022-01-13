<?php

\PoP\Root\App::getHookManager()->addFilter('excerpt_length', function($length) {
	return 300;
});

// Remove shortcodes from the excerpt
\PoP\Root\App::getHookManager()->addFilter('the_excerpt', 'strip_shortcodes', 0);
\PoP\Root\App::getHookManager()->addFilter('the_excerpt', 'make_clickable');
\PoP\Root\App::getHookManager()->removeFilter('the_excerpt', 'wpautop');
\PoP\Root\App::getHookManager()->addFilter('get_the_excerpt', 'strip_shortcodes', 0);
\PoP\Root\App::getHookManager()->addFilter('get_the_excerpt', 'make_clickable');
\PoP\Root\App::getHookManager()->addFilter('the_content', 'make_clickable');

// Remove empty whitspaces at the end of the article. Eg: when adding a Youtube video at the end, we gotta have the Youtube URL
// in 1 line so must add a break after it, but when it's the last thing in the article, at adds an ugly whitespace
// Do it before adding filter 'wpautop'
// Taken from https://secure.php.net/manual/en/function.trim.php
\PoP\Root\App::getHookManager()->addFilter('the_content', 'trim', 1);

function gdGetWebsiteDescription($addhomelink = true)
{
    $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
    return \PoP\Root\App::getHookManager()->applyFilters('gdGetWebsiteDescription', $cmsapplicationapi->getSiteDescription(), $addhomelink);
}
