<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
define ('POP_RESOURCELOADERCONFIGURATION_HOME_STATIC', 'static');
define ('POP_RESOURCELOADERCONFIGURATION_HOME_FEED', 'feed');

class PoPThemeWassup_ResourceLoader_Hooks {

    function __construct() {

        add_filter(
            'PoP_ResourceLoader_FileReproduction_Config:js-resources:home',
            array($this, 'get_home_resources'),
            10,
            2
        );
        add_filter(
            'PoP_ResourceLoader_FileReproduction_Config:js-resources:404',
            array($this, 'get_404_resources'),
            10,
            2
        );
        add_filter(
            'PoP_ResourceLoader_FileReproduction_Config:js-resources:tag',
            array($this, 'get_tag_resources'),
            10,
            2
        );
        add_filter(
            'PoP_ResourceLoader_FileReproduction_Config:js-resources:author',
            array($this, 'get_author_resources'),
            10,
            2
        );
        add_filter(
            'PoP_ResourceLoader_FileReproduction_Config:js-resources:single',
            array($this, 'get_single_resources'),
            10,
            2
        );
        add_filter(
            'PoP_ResourceLoader_FileReproduction_Config:js-resources:page',
            array($this, 'get_page_resources'),
            10,
            2
        );
        add_filter(
            'PoP_ResourceLoader_FileReproduction_Config:js-resources:page',
            array($this, 'get_backgroundurls_page_resources'),
            10,
            2
        );
    }

    function get_home_resources($resources, $fetching_json) {

        $template_id = GD_TEMPLATE_TOPLEVEL_HOME;

        // Home resources: there are 2 schemes:
        // 1. GetPoP: a single page
        // 2. MESYM: a feed of posts, with formats
        // Allow to select the right configuration through hooks
        $scheme = apply_filters(
            'PoPThemeWassup_ResourceLoader_HomeHooks:home-resources:scheme',
            POP_RESOURCELOADERCONFIGURATION_HOME_FEED
        );
        if ($scheme == POP_RESOURCELOADERCONFIGURATION_HOME_FEED) {

            $hierarchy = GD_SETTINGS_HIERARCHY_HOME;
            PoP_ResourceLoaderProcessorUtils::add_resources_from_settingsprocessors($fetching_json, $resources, $template_id, $hierarchy);
        }
        elseif ($scheme == POP_RESOURCELOADERCONFIGURATION_HOME_STATIC) {

            // Add a single item
            PoP_ResourceLoaderProcessorUtils::add_resources_from_current_vars($fetching_json, $resources, $template_id);
        }

        return $resources;
    }

    function get_404_resources($resources, $fetching_json) {

        $template_id = GD_TEMPLATE_TOPLEVEL_404;

        PoP_ResourceLoaderProcessorUtils::add_resources_from_current_vars($fetching_json, $resources, $template_id);

        return $resources;
    }

    function get_tag_resources($resources, $fetching_json) {

        // Get any one tag from the DB
        $query = array(
            'number' => 1,
            'fields' => 'ids',
        );
        if ($ids = get_tags($query)) {
        
            $template_id = GD_TEMPLATE_TOPLEVEL_TAG;
            $hierarchy = GD_SETTINGS_HIERARCHY_TAG;
            PoP_ResourceLoaderProcessorUtils::add_resources_from_settingsprocessors($fetching_json, $resources, $template_id, $hierarchy, $ids);
        }

        return $resources;
    }

    function get_author_resources($resources, $fetching_json) {

        // The author is a special case: different roles will have different configurations
        // However, we can't tell from the URL the role of that author (mesym.com/u/leo/ and mesym.com/u/mesym/)
        // So then, we gotta calculate the resources for both cases, and add them together
        // This way, loading any one author URL will load the resources needed for all different roles (Organization/Individual)
        $query = array(
            'number' => 1,
            'fields' => 'ID',
        );

        // We must merge together also the resources needed for the community!
        $community_ids = get_users(array_merge(
            $query,
            array(
                'role' => GD_URE_ROLE_COMMUNITY,
            )
        ));
        // The organization must be different than the community, so that they don't override each other in the $ids array
        // (otherwise, only the last one will prevail, and will not generate configuration for both source=community/organization)
        $organization_ids = get_users(array_merge(
            $query,
            array(
                'role' => GD_URE_ROLE_ORGANIZATION,
            ),
            $community_ids ?
                array(
                    'exclude' => $community_ids
                ) :
                array()
        ));
        $individual_ids = get_users(array_merge(
            $query,
            array(
                'role' => GD_URE_ROLE_INDIVIDUAL,
            )
        ));
        $ids = array_merge(
            $community_ids,
            $organization_ids,
            $individual_ids
        );
        if ($ids) {
        
            $template_id = GD_TEMPLATE_TOPLEVEL_AUTHOR;
            $hierarchy = GD_SETTINGS_HIERARCHY_AUTHOR;
            $merge = true;
            $options = array(
                'extra-vars' => array(
                    'source' => array(),
                ),
            );

            // For the organization and community, we must set the extra $vars['source'] value
            if ($organization_id = $organization_ids[0]) {

                $options['extra-vars']['source'][$organization_id] = GD_URLPARAM_URECONTENTSOURCE_ORGANIZATION;
            }
            if ($community_id = $community_ids[0]) {

                $options['extra-vars']['source'][$community_id] = GD_URLPARAM_URECONTENTSOURCE_COMMUNITY;
            }
            if ($individual_id = $individual_ids[0]) {

                $options['extra-vars']['source'][$individual_id] = '';
            }

            // Organization: it must add together the resources for both "source=community" and "source=organization"
            PoP_ResourceLoaderProcessorUtils::add_resources_from_settingsprocessors($fetching_json, $resources, $template_id, $hierarchy, $ids, $merge, $options);
        }

        return $resources;
    }

    function get_single_resources($resources, $fetching_json) {

        $template_id = GD_TEMPLATE_TOPLEVEL_SINGLE;
        $hierarchy = GD_SETTINGS_HIERARCHY_SINGLE;
        
        // Get one ID per category from the DB
        // We exclude POPTHEME_WASSUP_CAT_WEBPOSTS and POPTHEME_WASSUP_CAT_WEBPOSTLINKS because they are a special case:
        // Posts with these categories have the same path but different configuration.
        // So we gotta generate the configuration for these 2 using $merge = true
        $ids = array();
        $categories = get_terms(array(
            'taxonomy' => 'category',
            'fields' => 'ids',
            'exclude' => array(
                POPTHEME_WASSUP_CAT_WEBPOSTS,
                POPTHEME_WASSUP_CAT_WEBPOSTLINKS,
            ),
        ));
        // Allow to filter the categories. This is needed so that Articles/Announcements/etc similar-configuration categories can be generated only once,
        // and also because Articles and Article Links have different configurations but the same URL, so we gotta do $merge=true for these combinations
        $categories = apply_filters(
            'PoPThemeWassup_ResourceLoader_Hooks:single_resources:categories',
            $categories
        );
        $query = array(
            'posts_per_page' => 1,
            'fields' => 'ids',
        );
        foreach ($categories as $category) {

            if ($post_ids = get_posts(array_merge(
                $query,
                array(
                    'cat' => $category,
                )
            ))) {
                $ids[] = $post_ids[0];
            }
        }
        if ($ids) {

            $merge = false;
            PoP_ResourceLoaderProcessorUtils::add_resources_from_settingsprocessors($fetching_json, $resources, $template_id, $hierarchy, $ids, $merge);
        }

        
        // Now load POPTHEME_WASSUP_CAT_WEBPOSTS + POPTHEME_WASSUP_CAT_WEBPOSTLINKS combined
        $ids = array();

        // Load WebPost, make sure it doesn't have the Link category
        if ($post_ids = get_posts(array_merge(
            $query,
            array(
                'cat' => POPTHEME_WASSUP_CAT_WEBPOSTS,
                'category__not_in' => array(
                    POPTHEME_WASSUP_CAT_WEBPOSTLINKS,
                ),
            )
        ))) {
            $ids[] = $post_ids[0];
        }
        // Load WebPostLink
        if ($post_ids = get_posts(array_merge(
            $query,
            array(
                'category__and' => array(
                    POPTHEME_WASSUP_CAT_WEBPOSTS,
                    POPTHEME_WASSUP_CAT_WEBPOSTLINKS,
                ),
            )
        ))) {
            $ids[] = $post_ids[0];
        }
        if ($ids) {

            $merge = true;
            PoP_ResourceLoaderProcessorUtils::add_resources_from_settingsprocessors($fetching_json, $resources, $template_id, $hierarchy, $ids, $merge);
        }

        return $resources;
    }

    function get_page_resources($resources, $fetching_json) {
        
        $template_id = GD_TEMPLATE_TOPLEVEL_PAGE;
        $hierarchy = GD_SETTINGS_HIERARCHY_PAGE;
        PoP_ResourceLoaderProcessorUtils::add_resources_from_settingsprocessors($fetching_json, $resources, $template_id, $hierarchy);

        return $resources;
    }

    function get_backgroundurls_page_resources($resources, $fetching_json) {
                
        // The Initial Loaders page is a particular case: 
        // 1. blocks/formats for page POP_COREPROCESSORS_PAGE_LOADERS_INITIALFRAMES are not defined in file settingsprocessor.php, so method get_page_resources above will not work
        // 2. it must be handled for several targets, as configured through function get_loaders_initialframes()
        foreach (PoP_Frontend_ConfigurationUtils::get_backgroundload_pages() as $page_id => $targets) {
            
            $template_id = GD_TEMPLATE_TOPLEVEL_PAGE;
            $hierarchy = GD_SETTINGS_HIERARCHY_PAGE;
            $ids = array(
                $page_id,
            ); 
            $merge = false; 
            foreach ($targets as $target) {

                $components = array(
                    'target' => $target,
                );
                PoP_ResourceLoaderProcessorUtils::add_resources_from_current_vars($fetching_json, $resources, $template_id, $ids, $merge, $components);
            }
        }

        return $resources;
    }
}
    
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPThemeWassup_ResourceLoader_Hooks();
