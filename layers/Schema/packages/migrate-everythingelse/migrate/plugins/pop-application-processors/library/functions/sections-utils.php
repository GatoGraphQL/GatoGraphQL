<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Engine\Route\RouteUtils;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Routing\Routes as RoutingRoutes;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\Users\Facades\UserTypeAPIFacade;

class PoP_Module_Processor_CustomSectionBlocksUtils
{
    public static function getAuthorTitle()
    {
        $vars = ApplicationState::getVars();
        $userTypeAPI = UserTypeAPIFacade::getInstance();
        $author = $vars['routing-state']['queried-object-id'];
        $ret = $userTypeAPI->getUserDisplayName($author);

        $route = $vars['route'];
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
        $vars = ApplicationState::getVars();
        $applicationtaxonomyapi = \PoP\ApplicationTaxonomies\FunctionAPIFactory::getInstance();
        $tag_id = $vars['routing-state']['queried-object-id'];
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
            $route = $vars['route'];
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
        $vars = ApplicationState::getVars();
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $post_id = $vars['routing-state']['queried-object-id'];
        $ret = $customPostTypeAPI->getTitle($post_id);

        $route = $vars['route'];
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
        $vars = ApplicationState::getVars();
        $post_id = $vars['routing-state']['queried-object-id'];
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $cmsapplicationpostsapi = \PoP\Application\PostsFunctionAPIFactory::getInstance();
        if (in_array($customPostTypeAPI->getCustomPostType($post_id), $cmsapplicationpostsapi->getAllcontentPostTypes())) {
            return [PoP_Module_Processor_CustomSubMenus::class, PoP_Module_Processor_CustomSubMenus::MODULE_SUBMENU_SINGLE];
        }

        return null;
    }

    public static function addDataloadqueryargsAuthorcontent(&$ret)
    {
        $vars = ApplicationState::getVars();
        $author = $vars['routing-state']['queried-object-id'];

        // Allow to override with User Role Editor: for organizations: Find all the members of this community, and filter all posts accordingly
        // Only filter if the 'author' attribute has not been set yet. If it has been set, it must've been done by the filter,
        // which will allow only members belonging to the community. So use that one instead
        // if (!$ret['author']) {
        $authors = HooksAPIFacade::getInstance()->applyFilters('pop_module:dataload_query_args:authors', array($author));
        $ret['authors'] = $authors;
        // }
    }

    public static function addDataloadqueryargsTagcontent(&$ret)
    {
        $vars = ApplicationState::getVars();
        $tag_id = $vars['routing-state']['queried-object-id'];
        $ret['tag-ids'] = [$tag_id];
    }

    public static function addDataloadqueryargsTagsubscribers(&$ret)
    {
        $vars = ApplicationState::getVars();
        $tag_id = $vars['routing-state']['queried-object-id'];

        $ret['meta-query'][] = [
            'key' => \PoPSchema\UserMeta\Utils::getMetaKey(GD_METAKEY_PROFILE_SUBSCRIBESTOTAGS),
            'value' => $tag_id,
            'compare' => 'IN'
        ];
    }

    public static function addDataloadqueryargsAuthorfollowers(&$ret, $author = null)
    {
        if (!$author) {
            $vars = ApplicationState::getVars();
            $author = $vars['routing-state']['queried-object-id'];
        }

        // It must fulfil 2 conditions: the user must've said he/she's a member of this organization,
        // And the Organization must've accepted it by leaving the Show As Member privilege on
        $ret['meta-query'][] = [
            'key' => \PoPSchema\UserMeta\Utils::getMetaKey(GD_METAKEY_PROFILE_FOLLOWSUSERS),
            'value' => $author,
            'compare' => 'IN'
        ];
    }

    public static function addDataloadqueryargsAuthorfollowingusers(&$ret, $author = null)
    {
        if (!$author) {
            $vars = ApplicationState::getVars();
            $author = $vars['routing-state']['queried-object-id'];
        }

        // It must fulfil 2 conditions: the user must've said he/she's a member of this organization,
        // And the Organization must've accepted it by leaving the Show As Member privilege on
        $ret['meta-query'][] = [
            'key' => \PoPSchema\UserMeta\Utils::getMetaKey(GD_METAKEY_PROFILE_FOLLOWEDBY),
            'value' => $author,
            'compare' => 'IN'
        ];
    }

    public static function addDataloadqueryargsAuthorsubscribedtotags(&$ret)
    {
        $vars = ApplicationState::getVars();
        $author = $vars['routing-state']['queried-object-id'];

        // It must fulfil 2 conditions: the user must've said he/she's a member of this organization,
        // And the Organization must've accepted it by leaving the Show As Member privilege on
        $ret['meta-query'][] = [
            'key' => \PoPSchema\TaxonomyMeta\Utils::getMetaKey(GD_METAKEY_TERM_SUBSCRIBEDBY),
            'value' => $author,
            'compare' => 'IN'
        ];
    }

    public static function addDataloadqueryargsAuthorrecommendedposts(&$ret, $author = null)
    {
        $cmsapplicationpostsapi = \PoP\Application\PostsFunctionAPIFactory::getInstance();
        if (!$author) {
            $vars = ApplicationState::getVars();
            $author = $vars['routing-state']['queried-object-id'];
        }

        // Find all recommended posts by this author
        $ret['meta-query'][] = [
            'key' => \PoPSchema\CustomPostMeta\Utils::getMetaKey(GD_METAKEY_POST_RECOMMENDEDBY),
            'value' => array($author),
            'compare' => 'IN'
        ];
        $ret['custompost-types'] = $cmsapplicationpostsapi->getAllcontentPostTypes(); // Allow also Events post types, so these can be fetched from Stories (field references)
    }

    public static function addDataloadqueryargsSingleauthors(&$ret)
    {
        $vars = ApplicationState::getVars();
        $post_id = $vars['routing-state']['queried-object-id'];

        // Include only the authors of the current post
        $ret['include'] = gdGetPostauthors($post_id);
    }

    public static function addDataloadqueryargsRecommendedby(&$ret, $post_id = null)
    {
        if (!$post_id) {
            $vars = ApplicationState::getVars();
            $post_id = $vars['routing-state']['queried-object-id'];
        }

        // Find all related posts
        $ret['meta-query'][] = [
            'key' => \PoPSchema\UserMeta\Utils::getMetaKey(GD_METAKEY_PROFILE_RECOMMENDSPOSTS),
            'value' => array($post_id),
            'compare' => 'IN'
        ];
    }

    public static function addDataloadqueryargsUpvotedby(&$ret)
    {
        $vars = ApplicationState::getVars();
        $post_id = $vars['routing-state']['queried-object-id'];

        // Find all related posts
        $ret['meta-query'][] = [
            'key' => \PoPSchema\UserMeta\Utils::getMetaKey(GD_METAKEY_PROFILE_UPVOTESPOSTS),
            'value' => array($post_id),
            'compare' => 'IN'
        ];
    }

    public static function addDataloadqueryargsDownvotedby(&$ret)
    {
        $vars = ApplicationState::getVars();
        $post_id = $vars['routing-state']['queried-object-id'];

        // Find all related posts
        $ret['meta-query'][] = [
            'key' => \PoPSchema\UserMeta\Utils::getMetaKey(GD_METAKEY_PROFILE_DOWNVOTESPOSTS),
            'value' => array($post_id),
            'compare' => 'IN'
        ];
    }
}
