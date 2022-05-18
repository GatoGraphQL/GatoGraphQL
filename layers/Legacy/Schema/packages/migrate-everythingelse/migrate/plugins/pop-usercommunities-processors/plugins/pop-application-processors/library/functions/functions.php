<?php
use PoP\ComponentModel\State\ApplicationState;

/**
 * Allow communities to include its members' content in the community Profile
 */
\PoP\Root\App::addFilter('pop_componentVariation:dataload_query_args:authors', 'gdUreProfileCommunityDataloadqueryAddmembers');
function gdUreProfileCommunityDataloadqueryAddmembers($authors)
{
    $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);

    // Check if the user is showing the community. If showing user, then no need for this
    if (gdUreIsCommunity($author) && \PoP\Root\App::getState('source') == GD_URLPARAM_URECONTENTSOURCE_COMMUNITY) {
        $authors = array_merge(
            $authors,
            gdUreGetActivecontributingcontentcommunitymembers($author)
        );
    }

    return $authors;
}

\PoP\Root\App::addFilter('PoP_Module_Processor_CustomSectionBlocks:getDataloadSource:author', 'gdUreAddSourceParam', 10, 2);
function gdUreAddSourceParam($url, $user_id)
{
    if (gdUreIsCommunity($user_id)) {
        $source = \PoP\Root\App::getState('source');
        $url = PoP_URE_ModuleManager_Utils::addSource($url, $source);
    }

    return $url;
}

/**
 * Add the 'members' tab for the communities author page
 */
\PoP\Root\App::addFilter('PoP_Module_Processor_CustomSubMenus:author:routes', 'gdUreProfileCommunityAddMembersTab');
function gdUreProfileCommunityAddMembersTab($routes)
{
    $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
    if (gdUreIsCommunity($author) && defined('POP_USERCOMMUNITIES_ROUTE_MEMBERS') && POP_USERCOMMUNITIES_ROUTE_MEMBERS) {
        // Place the Members tab before the Followers tab
        $routes[POP_USERCOMMUNITIES_ROUTE_MEMBERS] = array();
    }

    return $routes;
}

// Add the source param whenever in an author
\PoP\Root\App::addFilter('gdUreAddSourceParamToSubmenu:skip:routes', 'gdUreAddSourceParamToSubmenuRoutes');
function gdUreAddSourceParamToSubmenuRoutes($routes)
{
    return array_merge(
        $routes,
        array(
            POP_USERCOMMUNITIES_ROUTE_MEMBERS,
            POP_SOCIALNETWORK_ROUTE_FOLLOWERS,
        )
    );
}
