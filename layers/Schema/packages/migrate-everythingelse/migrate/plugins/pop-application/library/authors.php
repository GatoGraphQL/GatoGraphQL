<?php
use PoP\ComponentModel\Misc\RequestUtils;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\Users\ConditionalOnComponent\CustomPosts\Facades\CustomPostUserTypeAPIFacade;
use PoPSchema\Users\Facades\UserTypeAPIFacade;

/**
 * Return the author of the post (to be overriden by Co-Authors plus)
 */
function gdGetPostauthors($post_id)
{
    $customPostUserTypeAPI = CustomPostUserTypeAPIFacade::getInstance();
    return HooksAPIFacade::getInstance()->applyFilters(
    	'gdGetPostauthors',
    	array($customPostUserTypeAPI->getAuthorID($post_id)),
    	$post_id
    );
}

function getAuthorProfileUrl($author)
{
    $userTypeAPI = UserTypeAPIFacade::getInstance();
    $url = $userTypeAPI->getUserURL($author);
    return RequestUtils::addRoute($url, POP_ROUTE_DESCRIPTION);
}

/**
 * Change Author permalink from 'author' to 'u'
 */
HooksAPIFacade::getInstance()->addFilter('author-base', function($authorBase) {
	return 'u';
});
