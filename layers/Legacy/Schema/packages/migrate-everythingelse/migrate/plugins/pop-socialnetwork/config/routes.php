<?php
use PoP\Definitions\Facades\DefinitionManagerFacade;
use PoP\Routing\DefinitionGroups;
$definitionManager = DefinitionManagerFacade::getInstance();

// Routes
//--------------------------------------------------------
if (!defined('POP_SOCIALNETWORK_ROUTE_FOLLOWERS')) {
	define('POP_SOCIALNETWORK_ROUTE_FOLLOWERS', $definitionManager->getUniqueDefinition('followers', DefinitionGroups::ROUTES));
}
if (!defined('POP_SOCIALNETWORK_ROUTE_FOLLOWINGUSERS')) {
	define('POP_SOCIALNETWORK_ROUTE_FOLLOWINGUSERS', $definitionManager->getUniqueDefinition('following-users', DefinitionGroups::ROUTES));
}
if (!defined('POP_SOCIALNETWORK_ROUTE_SUBSCRIBERS')) {
	define('POP_SOCIALNETWORK_ROUTE_SUBSCRIBERS', $definitionManager->getUniqueDefinition('subscribers', DefinitionGroups::ROUTES));
}
if (!defined('POP_SOCIALNETWORK_ROUTE_RECOMMENDEDPOSTS')) {
	define('POP_SOCIALNETWORK_ROUTE_RECOMMENDEDPOSTS', $definitionManager->getUniqueDefinition('recommended-posts', DefinitionGroups::ROUTES));
}
if (!defined('POP_SOCIALNETWORK_ROUTE_RECOMMENDEDBY')) {
	define('POP_SOCIALNETWORK_ROUTE_RECOMMENDEDBY', $definitionManager->getUniqueDefinition('recommended-by', DefinitionGroups::ROUTES));
}
if (!defined('POP_SOCIALNETWORK_ROUTE_UPVOTEDBY')) {
	define('POP_SOCIALNETWORK_ROUTE_UPVOTEDBY', $definitionManager->getUniqueDefinition('upvoted-by', DefinitionGroups::ROUTES));
}
if (!defined('POP_SOCIALNETWORK_ROUTE_DOWNVOTEDBY')) {
	define('POP_SOCIALNETWORK_ROUTE_DOWNVOTEDBY', $definitionManager->getUniqueDefinition('downvoted-by', DefinitionGroups::ROUTES));
}
if (!defined('POP_SOCIALNETWORK_ROUTE_CONTACTUSER')) {
	define('POP_SOCIALNETWORK_ROUTE_CONTACTUSER', $definitionManager->getUniqueDefinition('contact-user', DefinitionGroups::ROUTES));
}
if (!defined('POP_SOCIALNETWORK_ROUTE_FOLLOWUSER')) {
	define('POP_SOCIALNETWORK_ROUTE_FOLLOWUSER', $definitionManager->getUniqueDefinition('follow-user', DefinitionGroups::ROUTES));
}
if (!defined('POP_SOCIALNETWORK_ROUTE_UNFOLLOWUSER')) {
	define('POP_SOCIALNETWORK_ROUTE_UNFOLLOWUSER', $definitionManager->getUniqueDefinition('unfollow-user', DefinitionGroups::ROUTES));
}
if (!defined('POP_SOCIALNETWORK_ROUTE_SUBSCRIBETOTAG')) {
	define('POP_SOCIALNETWORK_ROUTE_SUBSCRIBETOTAG', $definitionManager->getUniqueDefinition('subscribe-to-tag', DefinitionGroups::ROUTES));
}
if (!defined('POP_SOCIALNETWORK_ROUTE_UNSUBSCRIBEFROMTAG')) {
	define('POP_SOCIALNETWORK_ROUTE_UNSUBSCRIBEFROMTAG', $definitionManager->getUniqueDefinition('unsubscribe-from-tag', DefinitionGroups::ROUTES));
}
if (!defined('POP_SOCIALNETWORK_ROUTE_RECOMMENDPOST')) {
	define('POP_SOCIALNETWORK_ROUTE_RECOMMENDPOST', $definitionManager->getUniqueDefinition('recommend-post', DefinitionGroups::ROUTES));
}
if (!defined('POP_SOCIALNETWORK_ROUTE_UNRECOMMENDPOST')) {
	define('POP_SOCIALNETWORK_ROUTE_UNRECOMMENDPOST', $definitionManager->getUniqueDefinition('unrecommend-post', DefinitionGroups::ROUTES));
}
if (!defined('POP_SOCIALNETWORK_ROUTE_UPVOTEPOST')) {
	define('POP_SOCIALNETWORK_ROUTE_UPVOTEPOST', $definitionManager->getUniqueDefinition('upvote-post', DefinitionGroups::ROUTES));
}
if (!defined('POP_SOCIALNETWORK_ROUTE_UNDOUPVOTEPOST')) {
	define('POP_SOCIALNETWORK_ROUTE_UNDOUPVOTEPOST', $definitionManager->getUniqueDefinition('undo-upvote-post', DefinitionGroups::ROUTES));
}
if (!defined('POP_SOCIALNETWORK_ROUTE_DOWNVOTEPOST')) {
	define('POP_SOCIALNETWORK_ROUTE_DOWNVOTEPOST', $definitionManager->getUniqueDefinition('downvote-post', DefinitionGroups::ROUTES));
}
if (!defined('POP_SOCIALNETWORK_ROUTE_UNDODOWNVOTEPOST')) {
	define('POP_SOCIALNETWORK_ROUTE_UNDODOWNVOTEPOST', $definitionManager->getUniqueDefinition('undo-downvote-post', DefinitionGroups::ROUTES));
}
if (!defined('POP_SOCIALNETWORK_ROUTE_SUBSCRIBEDTO')) {
	define('POP_SOCIALNETWORK_ROUTE_SUBSCRIBEDTO', $definitionManager->getUniqueDefinition('subscribed-to', DefinitionGroups::ROUTES));
}


\PoP\Root\App::getHookManager()->addFilter(
    \PoP\Routing\RouteHookNames::ROUTES,
    function($routes) {
    	return array_merge(
    		$routes,
    		[
				POP_SOCIALNETWORK_ROUTE_FOLLOWERS,
				POP_SOCIALNETWORK_ROUTE_FOLLOWINGUSERS,
				POP_SOCIALNETWORK_ROUTE_SUBSCRIBERS,
				POP_SOCIALNETWORK_ROUTE_RECOMMENDEDPOSTS,
				POP_SOCIALNETWORK_ROUTE_RECOMMENDEDBY,
				POP_SOCIALNETWORK_ROUTE_UPVOTEDBY,
				POP_SOCIALNETWORK_ROUTE_DOWNVOTEDBY,
				POP_SOCIALNETWORK_ROUTE_CONTACTUSER,
				POP_SOCIALNETWORK_ROUTE_FOLLOWUSER,
				POP_SOCIALNETWORK_ROUTE_UNFOLLOWUSER,
				POP_SOCIALNETWORK_ROUTE_SUBSCRIBETOTAG,
				POP_SOCIALNETWORK_ROUTE_UNSUBSCRIBEFROMTAG,
				POP_SOCIALNETWORK_ROUTE_RECOMMENDPOST,
				POP_SOCIALNETWORK_ROUTE_UNRECOMMENDPOST,
				POP_SOCIALNETWORK_ROUTE_UPVOTEPOST,
				POP_SOCIALNETWORK_ROUTE_UNDOUPVOTEPOST,
				POP_SOCIALNETWORK_ROUTE_DOWNVOTEPOST,
				POP_SOCIALNETWORK_ROUTE_UNDODOWNVOTEPOST,
				POP_SOCIALNETWORK_ROUTE_SUBSCRIBEDTO,
    		]
    	);
    }
);
