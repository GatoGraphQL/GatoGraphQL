<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\State\ApplicationState;

/**
 * Allow communities to include its members' content in the community Profile
 */
HooksAPIFacade::getInstance()->addFilter('pop_module:dataload_query_args:authors', 'gdUreProfileCommunityDataloadqueryAddmembers');
function gdUreProfileCommunityDataloadqueryAddmembers($authors)
{
    $vars = ApplicationState::getVars();
    $author = $vars['routing-state']['queried-object-id'];

    // Check if the user is showing the community. If showing user, then no need for this
    if (gdUreIsCommunity($author) && $vars['source'] == GD_URLPARAM_URECONTENTSOURCE_COMMUNITY) {
        $authors = array_merge(
            $authors,
            gdUreGetActivecontributingcontentcommunitymembers($author)
        );
    }

    return $authors;
}

HooksAPIFacade::getInstance()->addFilter('PoP_Module_Processor_CustomSectionBlocks:getDataloadSource:author', 'gdUreAddSourceParam', 10, 2);
function gdUreAddSourceParam($url, $user_id)
{
    if (gdUreIsCommunity($user_id)) {
        $vars = ApplicationState::getVars();
        $source = $vars['source'];
        $url = PoP_URE_ModuleManager_Utils::addSource($url, $source);
    }

    return $url;
}

/**
 * Add the 'members' tab for the communities author page
 */
HooksAPIFacade::getInstance()->addFilter('PoP_Module_Processor_CustomSubMenus:author:routes', 'gdUreProfileCommunityAddMembersTab');
function gdUreProfileCommunityAddMembersTab($routes)
{
    $vars = ApplicationState::getVars();
    $author = $vars['routing-state']['queried-object-id'];
    if (gdUreIsCommunity($author) && defined('POP_USERCOMMUNITIES_ROUTE_MEMBERS') && POP_USERCOMMUNITIES_ROUTE_MEMBERS) {
        // Place the Members tab before the Followers tab
        $routes[POP_USERCOMMUNITIES_ROUTE_MEMBERS] = array();
    }

    return $routes;
}

// Add the source param whenever in an author
HooksAPIFacade::getInstance()->addFilter('gdUreAddSourceParamToSubmenu:skip:routes', 'gdUreAddSourceParamToSubmenuRoutes');
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
