<?php
namespace PoP\Engine;

class Utils
{
    public static $errors = array();
    
    public static function getDomainId($domain)
    {

        // The domain ID is simply removing the scheme, and replacing all dots with '-'
        // It is needed to assign an extra class to the event
        $domain_id = str_replace('.', '-', removeScheme($domain));

        // Allow to override the domainId, to unify DEV and PROD domains
        return apply_filters('pop_modulemanager:domain_id', $domain_id, $domain);
    }

    public static function isSearchEngine()
    {
        return apply_filters('\PoP\Engine\Utils:isSearchEngine', false);
    }

    // // public static function getCheckpointConfiguration($page_id = null) {

    // //     return Settings\SettingsManager_Factory::getInstance()->getCheckpointConfiguration($page_id);
    // // }
    // public static function getCheckpoints($page_id = null) {

    //     return Settings\SettingsManager_Factory::getInstance()->getCheckpoints($page_id);
    // }

    // public static function isServerAccessMandatory($checkpoint_configuration) {

    //     // The Static type can be cached since it contains no data
    //     $dynamic_types = array(
    //         GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_DATAFROMSERVER,
    //     );
    //     $mandatory = in_array($checkpoint_configuration['type'], $dynamic_types);

    //     // Allow to add 'requires-user-state' by PoP UserState dependency
    //     return apply_filters(
    //         '\PoP\Engine\Utils:isServerAccessMandatory',
    //         $mandatory,
    //         $checkpoint_configuration
    //     );
    // }

    // public static function checkpointValidationRequired($checkpoint_configuration) {

    //     return true;
    //     // $type = $checkpoint_configuration['type'];
    //     // return (doingPost() && $type == GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_STATIC) || $type == GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_DATAFROMSERVER || $type == GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_STATELESS;
    // }

    public static function limitResults($results)
    {

        // Cut results if more than 4 times the established limit. This is to protect from hackers adding all post ids.
        $limit = 4 * getOption('posts_per_page');
        if (count($results) > $limit) {
            array_splice($results, $limit);
        }

        return $results;
    }

    public static function getCurrentUrl()
    {

        // Strip the Target and Output off it, users don't need to see those
        $remove_params = apply_filters(
            '\PoP\Engine\Utils:current_url:remove_params',
            array(
                GD_URLPARAM_SETTINGSFORMAT,
                GD_URLPARAM_VERSION,
                GD_URLPARAM_TARGET,
                GD_URLPARAM_MODULEFILTER,
                GD_URLPARAM_MODULEPATHS,
                GD_URLPARAM_HEADMODULE,
                GD_URLPARAM_ACTIONPATH,
                GD_URLPARAM_DATAOUTPUTITEMS,
                GD_URLPARAM_DATASOURCES,
                GD_URLPARAM_DATAOUTPUTMODE,
                GD_URLPARAM_DATABASESOUTPUTMODE,
                GD_URLPARAM_OUTPUT,
                GD_URLPARAM_DATASTRUCTURE,
                GD_URLPARAM_MANGLED,
                GD_URLPARAM_EXTRAURIS,
                GD_URLPARAM_ACTION, // Needed to remove ?action=preload, ?action=loaduserstate, ?action=loadlazy
            )
        );
        $url = remove_query_arg($remove_params, fullUrl());

        // Allow plug-ins to do their own logic to the URL
        $url = apply_filters('\PoP\Engine\Utils:getCurrentUrl', $url);

        return urldecode($url);
    }

    public static function getFramecomponentModules()
    {
        return apply_filters('\PoP\Engine\Utils:getFramecomponentModules', array());
    }

    public static function addTab($url, $page_id)
    {
        $tab = self::getTab($page_id);
        return add_query_arg(GD_URLPARAM_TAB, $tab, $url);
    }

    public static function getPagePath($page_id)
    {

        // Generate the page path. Eg: http://mesym.com/events/past/ will render events/past
        $permalink = getPermalink($page_id);

        // Comment Leo 22/05/2015: Use homeUrl instead of getSiteUrl so that it includes the language when using qTranslate X (eg: https://www.mesym.com/ms/)
        // $domain = trailingslashit(getSiteUrl());
        $domain = homeUrl();

        // Remove the domain from the permalink => page path
        $page_path = substr($permalink, strlen($domain));

        // Remove the first and last '/'
        $page_path = trim($page_path, '/');

        return $page_path;
    }

    public static function getPageUri($page_id)
    {

        // Generate the page URI. Eg: http://mesym.com/en/events/past/ will render /en/events/past/
        $permalink = getPermalink($page_id);
        $domain = getSiteUrl();

        // Remove the domain from the permalink => page path
        $page_uri = substr($permalink, strlen($domain));

        return $page_uri;
    }

    public static function getPostPath($post_id, $remove_post_slug = false)
    {

        // Generate the post path. If the post is published, just use permalink. If not, we can't, or it will get the URL to edit the post,
        // and not the URL the post will be published to, which is what is needed by the ResourceLoader
        if (getPostType($post_id) == 'publish') {
            $permalink = getPermalink($post_id);
            $post_name = getSlug($post_id);
        } else {
            // Function get_sample_permalink comes from the file below, so it must be included
            // Code below copied from `function get_sample_permalink_html`
            include_once ABSPATH.'wp-admin/includes/post.php';
            list($permalink, $post_name) = get_sample_permalink($post_id, null, null);
            $permalink = str_replace(array( '%pagename%', '%postname%' ), $post_name, $permalink);
        }

        $domain = trailingslashit(homeUrl());

        // Remove the domain from the permalink => page path
        $post_path = substr($permalink, strlen($domain));

        // Remove the post slug
        if ($remove_post_slug) {
            $post_path = substr($post_path, 0, strlen($post_path) - strlen(trailingslashit($post_name)));
        }

        return $post_path;
    }

    public static function getCategoryPath($category_id, $taxonomy = 'category')
    {

        // Convert it to int, otherwise it thinks it's a string and the method below fails
        $category_path = get_term_link((int) $category_id, $taxonomy);

        // Remove the initial part ("https://www.mesym.com/en/categories/")
        global $wp_rewrite;
        $termlink = $wp_rewrite->get_extra_permastruct($taxonomy);
        $termlink = str_replace("%$taxonomy%", '', $termlink);
        $termlink = homeUrl(user_trailingslashit($termlink, $taxonomy));

        return substr($category_path, strlen($termlink));
    }

    public static function getTab($page_id)
    {

        // Add url with the tab pointing to the corresponding page
        return self::getPagePath($page_id);
    }

    public static function getHierarchyPageId()
    {
        $vars = Engine_Vars::getVars();
        $hierarchy = $vars['hierarchy'];
        if ($vars['global-state']['is-page']) {
            $page_id = $vars['global-state']['queried-object-id'];
        } elseif ($vars['global-state']['is-home'] || $vars['global-state']['is-front-page']) {
            $page_id = self::getHierarchyDefaultPage($hierarchy);
        } elseif ($vars['global-state']['is-author'] || $vars['global-state']['is-single'] || $vars['global-state']['is-tag']) {
            // Get the page from the tab attr
            if ($tab = $vars['tab']) {
                $page = getPageByPath($tab);
                $page_id = $page->ID;
            }
            // Otherwise, get the default page for each hierarchy
            else {
                $page_id = self::getHierarchyDefaultPage($hierarchy);
            }
        } elseif ($vars['global-state']['is-404']) {
            $page_id = self::getHierarchyDefaultPage($hierarchy);
        }
        // Comment Leo 12/04/2017: there is a problem, in which calling
        // https://www.mesym.com/en/stories/ attempts to load the "stories" category template
        // (even though stories is located under category "posts"). When that happens, since
        // PoP currently doesn't support categories, then simply treat it as a 404
        elseif ($vars['global-state']['is-category'] || $vars['global-state']['is-archive']) {
            $page_id = self::getHierarchyDefaultPage($hierarchy);
        }

        return $page_id;
    }

    public static function getHierarchyDefaultPage($hierarchy)
    {
        $default_pages = array(
            GD_SETTINGS_HIERARCHY_HOME => POPENGINE_PAGEPLACEHOLDER_HOME,
            GD_SETTINGS_HIERARCHY_TAG => POPENGINE_PAGEPLACEHOLDER_TAG,
            GD_SETTINGS_HIERARCHY_SINGLE => POPENGINE_PAGEPLACEHOLDER_SINGLE,
            GD_SETTINGS_HIERARCHY_AUTHOR => POPENGINE_PAGEPLACEHOLDER_AUTHOR,
            GD_SETTINGS_HIERARCHY_404 => POPENGINE_PAGEPLACEHOLDER_404,
        );
    
        // Comment Leo 12/04/2017: there is a problem, in which calling
        // https://www.mesym.com/en/stories/ attempts to load the "stories" category template
        // (even though stories is located under category "posts"). When that happens, since
        // PoP currently doesn't support categories, then simply treat it as a 404
        $default_pages[GD_SETTINGS_HIERARCHY_CATEGORY] = POPENGINE_PAGEPLACEHOLDER_404;
        $default_pages[GD_SETTINGS_HIERARCHY_ARCHIVE] = POPENGINE_PAGEPLACEHOLDER_404;

        return $default_pages[$hierarchy];
    }

    public static function getDatastructureFormatter()
    {
        $vars = Engine_Vars::getVars();

        $datastructureformat_manager = DataStructureFormat_Manager_Factory::getInstance();
        return $datastructureformat_manager->getDatastructureFormatter($vars['datastructure']);
    }

    public static function fetchingSite()
    {
        $vars = Engine_Vars::getVars();
        return $vars['fetching-site'];
    }

    public static function loadingSite()
    {

        // If we are doing JSON (or any other output) AND we setting the target, then we're loading content dynamically and we need it to be JSON
        // Otherwise, it is the first time loading website => loadingSite
        $vars = Engine_Vars::getVars();
        return $vars['loading-site'];
    }

    public static function isPage($page_id_or_ids)
    {
        $vars = Engine_Vars::getVars();
        if ($vars['global-state']['is-page']) {
            $vars_page_id = $vars['global-state']['queried-object-id'];
            if (is_array($page_id_or_ids)) {
                $page_ids = $page_id_or_ids;
                return in_array($vars_page_id, $page_ids);
            }

            $page_id = $page_id_or_ids;
            return $page_id == $vars_page_id;
        }

        return false;
    }
}
