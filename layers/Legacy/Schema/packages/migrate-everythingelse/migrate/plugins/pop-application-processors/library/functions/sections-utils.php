<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Routing\Routes as RoutingRoutes;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPCMSSchema\Users\Facades\UserTypeAPIFacade;

class PoP_Module_Processor_CustomSectionBlocksUtils
{
    public static function getAuthorTitle()
    {
        $userTypeAPI = UserTypeAPIFacade::getInstance();
        $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
        $ret = $userTypeAPI->getUserDisplayName($author);

        $route = \PoP\Root\App::getState('route');
        if ($route != RoutingRoutes::$MAIN) {
            $ret = sprintf(
                '<small>%s <i class="fa fa-fw fa-angle-double-right"></i></small> %s',
                $ret,
                RouteUtils::getRouteTitle($route)
            );
        }
        return $ret;
    }

    public static function getTagTitle($add_description = true, $add_sublevel = true)
    {
        $applicationtaxonomyapi = \PoP\ApplicationTaxonomies\FunctionAPIFactory::getInstance();
        $tag_id = \PoP\Root\App::getState(['routing', 'queried-object-id']);
        $ret = '<i class="fa fa-fw fa-hashtag"></i>' . $applicationtaxonomyapi->getTagSymbolName($tag_id);

        if ($add_description) {
            // tag_description wraps the description in a <p>, remove it
            $description = trim(str_replace(array('<p>', '</p>'), '', tag_description()));
            if ($description) {
                $ret = sprintf(
                    TranslationAPIFacade::getInstance()->__('%1$s (%2$s)', 'poptheme-wassup'),
                    $ret,
                    $description
                );
            }
        }

        if ($add_sublevel) {
            $route = \PoP\Root\App::getState('route');
            if ($route != RoutingRoutes::$MAIN) {
                $ret = sprintf(
                    '<small>%s <i class="fa fa-fw fa-angle-double-right"></i></small> %s',
                    $ret,
                    RouteUtils::getRouteTitle($route)
                );
            }
        }
        return $ret;
    }

    public static function getSingleTitle()
    {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $post_id = \PoP\Root\App::getState(['routing', 'queried-object-id']);
        $ret = $customPostTypeAPI->getTitle($post_id);

        $route = \PoP\Root\App::getState('route');
        if ($route != RoutingRoutes::$MAIN) {
            $ret = sprintf(
                '<small>%s <i class="fa fa-fw fa-angle-double-right"></i></small> %s',
                $ret,
                RouteUtils::getRouteTitle($route)
            );
        }

        return $ret;
    }

    public static function getSingleSubmenu()
    {
        $post_id = \PoP\Root\App::getState(['routing', 'queried-object-id']);
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $cmsapplicationpostsapi = \PoP\Application\PostsFunctionAPIFactory::getInstance();
        if (in_array($customPostTypeAPI->getCustomPostType($post_id), $cmsapplicationpostsapi->getAllcontentPostTypes())) {
            return [PoP_Module_Processor_CustomSubMenus::class, PoP_Module_Processor_CustomSubMenus::MODULE_SUBMENU_SINGLE];
        }

        return null;
    }

    public static function addDataloadqueryargsAuthorcontent(&$ret)
    {
        $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);

        // Allow to override with User Role Editor: for organizations: Find all the members of this community, and filter all posts accordingly
        // Only filter if the 'author' attribute has not been set yet. If it has been set, it must've been done by the filter,
        // which will allow only members belonging to the community. So use that one instead
        // if (!$ret['author']) {
        $authors = \PoP\Root\App::applyFilters('pop_componentVariation:dataload_query_args:authors', array($author));
        $ret['authors'] = $authors;
        // }
    }

    public static function addDataloadqueryargsTagcontent(&$ret)
    {
        $tag_id = \PoP\Root\App::getState(['routing', 'queried-object-id']);
        $ret['tag-ids'] = [$tag_id];
    }

    public static function addDataloadqueryargsTagsubscribers(&$ret)
    {
        $tag_id = \PoP\Root\App::getState(['routing', 'queried-object-id']);

        $ret['meta-query'][] = [
            'key' => \PoPCMSSchema\UserMeta\Utils::getMetaKey(GD_METAKEY_PROFILE_SUBSCRIBESTOTAGS),
            'value' => $tag_id,
            'compare' => 'IN'
        ];
    }

    public static function addDataloadqueryargsAuthorfollowers(&$ret, $author = null)
    {
        if (!$author) {
            $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
        }

        // It must fulfil 2 conditions: the user must've said he/she's a member of this organization,
        // And the Organization must've accepted it by leaving the Show As Member privilege on
        $ret['meta-query'][] = [
            'key' => \PoPCMSSchema\UserMeta\Utils::getMetaKey(GD_METAKEY_PROFILE_FOLLOWSUSERS),
            'value' => $author,
            'compare' => 'IN'
        ];
    }

    public static function addDataloadqueryargsAuthorfollowingusers(&$ret, $author = null)
    {
        if (!$author) {
            $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
        }

        // It must fulfil 2 conditions: the user must've said he/she's a member of this organization,
        // And the Organization must've accepted it by leaving the Show As Member privilege on
        $ret['meta-query'][] = [
            'key' => \PoPCMSSchema\UserMeta\Utils::getMetaKey(GD_METAKEY_PROFILE_FOLLOWEDBY),
            'value' => $author,
            'compare' => 'IN'
        ];
    }

    public static function addDataloadqueryargsAuthorsubscribedtotags(&$ret)
    {
        $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);

        // It must fulfil 2 conditions: the user must've said he/she's a member of this organization,
        // And the Organization must've accepted it by leaving the Show As Member privilege on
        $ret['meta-query'][] = [
            'key' => \PoPCMSSchema\TaxonomyMeta\Utils::getMetaKey(GD_METAKEY_TERM_SUBSCRIBEDBY),
            'value' => $author,
            'compare' => 'IN'
        ];
    }

    public static function addDataloadqueryargsAuthorrecommendedposts(&$ret, $author = null)
    {
        $cmsapplicationpostsapi = \PoP\Application\PostsFunctionAPIFactory::getInstance();
        if (!$author) {
            $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
        }

        // Find all recommended posts by this author
        $ret['meta-query'][] = [
            'key' => \PoPCMSSchema\CustomPostMeta\Utils::getMetaKey(GD_METAKEY_POST_RECOMMENDEDBY),
            'value' => array($author),
            'compare' => 'IN'
        ];
        $ret['custompost-types'] = $cmsapplicationpostsapi->getAllcontentPostTypes(); // Allow also Events post types, so these can be fetched from Stories (field references)
    }

    public static function addDataloadqueryargsSingleauthors(&$ret)
    {
        $post_id = \PoP\Root\App::getState(['routing', 'queried-object-id']);

        // Include only the authors of the current post
        $ret['include'] = gdGetPostauthors($post_id);
    }

    public static function addDataloadqueryargsRecommendedby(&$ret, $post_id = null)
    {
        if (!$post_id) {
            $post_id = \PoP\Root\App::getState(['routing', 'queried-object-id']);
        }

        // Find all related posts
        $ret['meta-query'][] = [
            'key' => \PoPCMSSchema\UserMeta\Utils::getMetaKey(GD_METAKEY_PROFILE_RECOMMENDSPOSTS),
            'value' => array($post_id),
            'compare' => 'IN'
        ];
    }

    public static function addDataloadqueryargsUpvotedby(&$ret)
    {
        $post_id = \PoP\Root\App::getState(['routing', 'queried-object-id']);

        // Find all related posts
        $ret['meta-query'][] = [
            'key' => \PoPCMSSchema\UserMeta\Utils::getMetaKey(GD_METAKEY_PROFILE_UPVOTESPOSTS),
            'value' => array($post_id),
            'compare' => 'IN'
        ];
    }

    public static function addDataloadqueryargsDownvotedby(&$ret)
    {
        $post_id = \PoP\Root\App::getState(['routing', 'queried-object-id']);

        // Find all related posts
        $ret['meta-query'][] = [
            'key' => \PoPCMSSchema\UserMeta\Utils::getMetaKey(GD_METAKEY_PROFILE_DOWNVOTESPOSTS),
            'value' => array($post_id),
            'compare' => 'IN'
        ];
    }
}
