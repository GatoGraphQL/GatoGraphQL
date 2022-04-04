<?php

define('GD_CLASS_LAZYJS', 'pop-lazyjs');
define('GD_CLASS_SPINNER', 'spinner');

define('GD_CLASS_FOLLOWUSER', 'pop-followuser');
define('GD_CLASS_UNFOLLOWUSER', 'pop-unfollowuser');
define('GD_CLASS_RECOMMENDPOST', 'pop-recommendpost');
define('GD_CLASS_UNRECOMMENDPOST', 'pop-unrecommendpost');
define('GD_CLASS_SUBSCRIBETOTAG', 'pop-subscribetotag');
define('GD_CLASS_UNSUBSCRIBEFROMTAG', 'pop-unsubscribefromtag');
define('GD_CLASS_UPVOTEPOST', 'pop-upvotepost');
define('GD_CLASS_UNDOUPVOTEPOST', 'pop-undoupvotepost');
define('GD_CLASS_DOWNVOTEPOST', 'pop-downvotepost');
define('GD_CLASS_UNDODOWNVOTEPOST', 'pop-undodownvotepost');

define('GD_CLASS_TRIGGERLAYOUT', 'trigger-layout');

\PoP\Root\App::addFilter('gd_jquery_constants', gdPopcoreClassesJqueryConstants(...));
function gdPopcoreClassesJqueryConstants($jqueryConstants)
{
    $jqueryConstants['CLASS_LAZYJS'] = GD_CLASS_LAZYJS;
    $jqueryConstants['CLASS_TRIGGERLAYOUT'] = GD_CLASS_TRIGGERLAYOUT;
    return $jqueryConstants;
}

function gdClassesBody()
{
    if (function_exists('body_class')) {
        return implode(' ', \PoP\Root\App::applyFilters('gdClassesBody', array()));
    }

    return '';
}

/*
 * Add extra classes to the body: Web Platform
 * Then it is possible to hide elements in the Media Library in the webplatform
 */
\PoP\Root\App::addFilter("gdClassesBody", 'gdClassesBodyWebPlatformImpl');
function gdClassesBodyWebPlatformImpl($body_classes)
{

    // User role
    $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
    if (!$cmsapplicationapi->isAdminPanel()) {
        $body_classes[] = "webplatform";
    }
    
    return $body_classes;
}
