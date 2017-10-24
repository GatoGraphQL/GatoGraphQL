<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_UTILS_TEMPLATE_PREFIX', 'tmpl-');

class PoP_ResourceLoaderProcessorUtils {

    protected static $initialized = false;
    protected static $bundle_ids, $bundle_counter, $bundlegroup_ids, $bundlegroup_counter, $key_ids, $key_counter;

    public static function init() {

        if (!self::$initialized) {

            self::$initialized = true;

            // Get the already generated abbreviations from the cache
            global $pop_resourceloader_abbreviationsstorage_manager;
            if ($pop_resourceloader_abbreviationsstorage_manager->has_cached_abbreviations()) {

                self::$bundle_ids = $pop_resourceloader_abbreviationsstorage_manager->get_bundle_ids();
                self::$bundlegroup_ids = $pop_resourceloader_abbreviationsstorage_manager->get_bundlegroup_ids();
                self::$key_ids = $pop_resourceloader_abbreviationsstorage_manager->get_key_ids();

                // Start the counter in 1 plus than the elements we already have (actually, the counter should not be needed!)
                self::$bundle_counter = count(self::$bundle_ids) + 1;
                self::$bundlegroup_counter = count(self::$bundlegroup_ids) + 1;
                self::$key_counter = count(self::$key_ids) + 1;
            }
            else {

                self::$bundle_ids = self::$bundlegroup_ids = self::$key_ids = array();

                // Start the counter in 1, so we have an hashmap instead of an array on the generated file
                self::$bundle_counter = self::$bundlegroup_counter = self::$key_counter = 1;
            }
        }
    }

    public static function delete_abbreviations() {

        // Get the already generated abbreviations from the cache
        global $pop_resourceloader_abbreviationsstorage_manager;
        $pop_resourceloader_abbreviationsstorage_manager->delete();

        // Re-initialize the inner variables
        self::$initialized = false;
        self::init();
    }

    public static function save_abbreviations() {

        // Get the already generated abbreviations from the cache
        global $pop_resourceloader_abbreviationsstorage_manager;
        $pop_resourceloader_abbreviationsstorage_manager->save(self::$bundle_ids, self::$bundlegroup_ids, self::$key_ids);
    }

    public static function chunk_resources($resources_set, $chunk_size = 4) {

        // Further divide each array into chunks, to maximize the possibilities of different pages sharing the same bundles
        $chunked_resources = array();
        foreach ($resources_set as $resources_item) {

            $chunked_resources = array_merge(
                $chunked_resources,
                array_chunk($resources_item, $chunk_size)
            );
        }

        return $chunked_resources;
    }

    public static function get_pages_and_formats_added_under_hierarchy($hierarchy) {

        global $gd_template_settingsprocessor_manager;

        $page_formats = array();
        if ($pages = $gd_template_settingsprocessor_manager->get_pages_added_under_hierarchy($hierarchy)) {
                
            foreach ($pages as $page_id) {

                // "false" or "0" id pages are added to the configuration when that page has not been defined. Check for this case and skip it
                if (!$page_id) {
                    continue;
                }
                
                $settingsprocessor = $gd_template_settingsprocessor_manager->get_processor_by_page($page_id, $hierarchy);

                // If this page is for internal use (eg: System Build/Generate/Install), then do not print out in the configuration
                // Users should not be made aware of this path!
                $internals = $settingsprocessor->is_for_internal_use($hierarchy);
                if ($internals && $internals[$page_id]) {
                    continue;
                }
                
                // Get the blocks and blockgroups defined in the settings processor
                // It has the following format: $page_blockunits[$page_id]['block(group)s']
                $page_blocks = $settingsprocessor->get_page_blocks($hierarchy) ?? array();
                $page_blockgroups = $settingsprocessor->get_page_blockgroups($hierarchy) ?? array();

                // Extract all the formats, and save it under the return variable
                $page_formats[$page_id] = array_merge(
                    $page_blocks[$page_id] && $page_blocks[$page_id]['blocks'] ? array_keys($page_blocks[$page_id]['blocks']) : array(),
                    $page_blockgroups[$page_id] && $page_blockgroups[$page_id]['blockgroups'] ? array_keys($page_blockgroups[$page_id]['blockgroups']) : array()
                );
            }
        }

        return $page_formats;
    }

    public static function add_resources_from_settingsprocessors(&$resources, $template_id, $hierarchy, $ids = array(), $merge = false) {

        // Get all the formats that have been set for page POP_WPAPI_PAGE_ALLCONTENT
        global $gd_template_processor_manager;

        // Keep the original values in the $vars, since they'll need to be changed to pretend we are in a different $request
        $vars = &GD_TemplateManager_Utils::$vars;

        // Iterate through all the pages added as configuration for this hierarchy,
        // and all the resources for each
        if ($page_formats = self::get_pages_and_formats_added_under_hierarchy($hierarchy)) {

            // If there is more than one page, then add the tabs component (eg: feeds)
            // If there is only one page defined, then there is no need for the tabs (eg: homepage)
            $add_tabs = ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE) ? false : count($page_formats) > 1;
            foreach ($page_formats as $page_id => $formats) {

                foreach ($formats as $format) {
                    
                    $options = array();
                    $components = array(
                        'format' => $format,
                    );
                    $original_layouts = array();
                    if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE) {

                        $ids = array($page_id);

                        // Special case: if the page is one of those that accepts to change its modules through $_REQUEST['layouts'], then add all these layouts to $vars,
                        // to generate the configuration stating to need all potential resources
                        // These pages are: POP_WPAPI_PAGE_LOADERS_POSTS_LAYOUTS, POP_WPAPI_PAGE_LOADERS_USERS_LAYOUTS, POP_WPAPI_PAGE_LOADERS_COMMENTS_LAYOUTS, POP_WPAPI_PAGE_LOADERS_TAGS_LAYOUTS
                        global $gd_dataquery_manager;
                        if (in_array($page_id, $gd_dataquery_manager->get_cacheablepages())) {

                            // Save the original layouts value
                            $original_layouts = $vars['layouts'];

                            // Get all possible layouts values, and add all of them to $vars
                            $vars['layouts'] = $gd_dataquery_manager->get_allowedlayouts();
                        }
                    }
                    if ($add_tabs) {

                        $components['tab'] = GD_TemplateManager_Utils::get_tab($page_id);

                        // If this tab is the default one, an entry with no tab must also be created
                        if ($page_id == GD_TemplateManager_Utils::get_hierarchy_default_page($hierarchy)) {
                            $options['is-default-tab'] = true;
                        }
                    }
                    self::add_resources_from_current_vars($resources, $template_id, $ids, $merge, $components, $options);

                    // Restore the original $vars['layouts']
                    if ($original_layouts) {

                        $vars['layouts'] = $original_layouts;
                    }
                }
            }
        }
    }

    public static function get_key_id($key) {

        self::init();
        return self::get_string_id($key, self::$key_ids, self::$key_counter);
    }

    protected static function get_string_id($string, &$string_ids, &$string_counter) {

        // Instead of calculating a hash, simply keep a counter, in order to further reduce the size of the generated file
        $string_id = $string_ids[$string];
        if (!is_null($string_id)) { // It could be 0

            return $string_id;
        }

        // It is not there yet, create a new entry and return it
        $string_id = $string_counter++;
        $string_ids[$string] = $string_id;
        return $string_id;
    }

    public static function get_bundle_id($resources) {

        self::init();
        return self::get_set_id($resources, self::$bundle_ids, self::$bundle_counter);
    }

    public static function get_bundlegroup_id($bundles) {

        self::init();
        return self::get_set_id($bundles, self::$bundlegroup_ids, self::$bundlegroup_counter);
    }

    protected static function get_set_id($set, &$set_ids, &$set_counter) {

        // Order them, so that 2 sets with the same resources, but on different order, are still considered the same
        array_multisort($set);

        // Transform into a string
        $encoded = json_encode($set);

        // // Calculate the hash from that bundle of resources
        // return md5($encoded);

        // Instead of calculating a hash, simply keep a counter, in order to further reduce the size of the generated file
        $set_id = $set_ids[$encoded];
        if (!is_null($set_id)) { // It could be 0

            return $set_id;
        }

        // It is not there yet, create a new entry and return it
        $set_id = $set_counter++;
        $set_ids[$encoded] = $set_id;
        return $set_id;
    }

    public static function get_noconflict_resource_name($resource) {

        // Add 'pop-' before the registered name, to avoid conflicts with external parties (eg: WP also registers script "utils")
        return 'pop-'.$resource;
    }

	public static function get_template_resource_name($template_source) {
        
        // return POP_RESOURCELOADER_UTILS_TEMPLATE_PREFIX.$template_source;
        global $pop_templateresourceloaderprocessor_manager;
        return $pop_templateresourceloaderprocessor_manager->get_resource($template_source);
    }

    public static function get_template_source($resource) {
        
        return substr($resource, strlen(POP_RESOURCELOADER_UTILS_TEMPLATE_PREFIX));
    }

    public static function get_template_resources($template_sources) {

        return array_map(array('PoP_ResourceLoaderProcessorUtils', 'get_template_resource_name'), $template_sources);
    }

    public static function calculate_resources($template_sources, $methods) {

        global $pop_resourceloaderprocessor_manager, $pop_templateresourceloaderprocessor_manager;

        // Important: do not change the order of these keys. In the Javascript it will fetch the elements in this order:
        // first external scripts, then JS (because templates may depend on them, such as em-calendar-inner.tmpl) and finally the templates
        // $resources = array(
        //     'external' => array(),
        //     'js' => array(),
        //     'templates' => array(),
        // );
        $resources = array();
        
        // Make sure there are no duplicates
        $template_sources = array_unique($template_sources);
        $methods = array_unique($methods);

        // Convert the template-sources to the corresponding resources
        $template_resources = self::get_template_resources($template_sources);
        // if ($template_resources) {
        //     $resources['templates'] = $template_resources;
        // }

        // Add all the JS dependencies from the templates, and the templates themselves
        // $dependency_resources = array();
        foreach ($template_resources as $template_resource) {

            $pop_resourceloaderprocessor_manager->add_resource_dependencies($resources/*$dependency_resources*/, $template_resource, true/*, 'templates'*/);
        }
        // if ($dependency_resources) {
        //     $resources[] = $dependency_resources;
        // }

        // $method_resources = $pop_resourceloaderprocessor_manager->get_resources_from_jsmethods($methods, $template_resources);
        // if ($method_resources) {
        //     foreach ($method_resources as $group => $group_resources) {
        //         $resources[$group] = array_unique(array_merge(
        //             $resources[$group],
        //             $group_resources
        //         ));
        //     }
        // }
        $pop_resourceloaderprocessor_manager->add_resources_from_jsmethods($resources, $methods, $template_resources);

        // From all the collected methods, calculate the JS resources needed
        // Also add the template extensions, since they won't be referenced directly inside template-sources
        // return array_values(array_unique(array_merge(
        //     $js_resources,
        //     $pop_resourceloaderprocessor_manager->get_resources_from_jsmethods($methods, $template_resources)
        // )));

        // Drop the groups, $resources will be an array of 3 arrays
        // return array_values($resources);
        return $resources;
    }

	public static function add_resources_from_current_vars(&$resources, $toplevel_template_id, $ids = array(), $merge = false, $components = array(), $options = array()) {
        
        // Use the $vars identifier to store the wrapper cache, so there is no collision with the values saved for the current request
        global $gd_template_processor_runtimecache, $gd_template_processor_manager, $pop_resourceloaderprocessor_manager;
        $gd_template_processor_runtimecache->setUseVarsIdentifier(true);

        // Keep the original values in the $vars, since they'll need to be changed to pretend we are in a different $request
        $vars = &GD_TemplateManager_Utils::$vars;

        // IMPORTANT: we must pretend it's 'fetching-json' request, so that it doesn't load the frame files once again, which will be already loaded (PRPL is triggered when clicking on any link => will always be doing ?output=json)
        $original_vars = array();
        $vars_keys = array(
            'output',
            'fetching-json',
            'fetching-json-settingsdata',
            'fetching-json-settings',
            'fetching-json-data',
            // Variables over which the composition of different blocks depends
            'format',
            'tab',
            'target',
            // Hierarchy
            'global-state',
        );
        foreach ($vars_keys as $vars_key) {
            $original_vars[$vars_key] = $vars[$vars_key];
        }
        // $original_output = $vars['output'];
        // $original_fetchingjson = $vars['fetching-json'];
        // $original_fetchingjsonsettingsdata = $vars['fetching-json-settingsdata'];
        // $original_fetchingjsonsettings = $vars['fetching-json-settings'];
        // $original_fetchingjsondata = $vars['fetching-json-data'];
        
        // // Variables over which the composition of different blocks depends
        // $original_format = $vars['format'];
        // $original_tab = $vars['tab'];
        // $original_target = $vars['target'];

        // // Hierarchy
        // $original_hierarchy = $vars['global-state']['hierarchy'];

        // Obtain the key under which to add the resources, which is a combination of components 'format', 'tab' and 'target'
        // This code is replicated in function `loadResources` in resourceloader.js
        $params = array();
        $format = $components['format'] ?? POP_VALUES_DEFAULT;
		$tab = $components['tab'];
        
        // Targets special cases: certain formats (eg: Navigator) are used only from a corresponding target
        // So if we have that format, use the correponding target, or if not, the default is main
        // Give priority to $components['target'] though, so if we set this value, then it will use that value
        // Then, when processing POP_EM_POPPROCESSORS_PAGE_ADDLOCATION, we can have a configuration for both target=main and target=modals
        // Then, also set the format as the "default" one, because these pages will never be called using format="navigator" (etc), there will be no format whatsoever
        $duplicate_as_default_format = false;
        if ($components['target']) {

            $target = $components['target'];
        }
        else {

            $format_targets = apply_filters(
                'PoP_ResourceLoaderProcessorUtils:resources-from-current-vars:format-targets',
                array()
            );
            if ($format_targets[$format]) {

                // Notice that we are not changing here the format to default, but say to duplicate the entry
                // This is to avoid having complete entries in the corresponding resourceloader-config-....js file
                // (such as config-resources-pagenavigator.js), because it treats an empty array as "[]" in JSON, 
                // instead of "{}", which may make the JS produce an error
                $target = $format_targets[$format];
                $duplicate_as_default_format = true;
                // $format = POP_VALUES_DEFAULT;
            }
            else {

                $target = GD_URLPARAM_TARGET_MAIN;
            }
        }
        
		$params[] = POP_RESOURCELOADERIDENTIFIER_FORMAT.$format;
		if ($tab) {
			$params[] = POP_RESOURCELOADERIDENTIFIER_TAB.$tab;
		}
		$params[] = POP_RESOURCELOADERIDENTIFIER_TARGET.$target;

		$key = implode(GD_SEPARATOR_RESOURCELOADER, $params);
        
        // Pretend we are in that intended page, by setting the $vars in accordance
        $vars['output'] = GD_URLPARAM_OUTPUT_JSON;
        $vars['fetching-json'] = true;
        $vars['fetching-json-settingsdata'] = true;
        $vars['fetching-json-settings'] = false;
        $vars['fetching-json-data'] = false;
        $vars['format'] = $format;
        $vars['tab'] = $tab;
        $vars['target'] = $target;

        $hierarchies = array(
            GD_TEMPLATE_TOPLEVEL_HOME => 'home',
            GD_TEMPLATE_TOPLEVEL_TAG => 'tag',
            GD_TEMPLATE_TOPLEVEL_PAGE => 'page',
            GD_TEMPLATE_TOPLEVEL_SINGLE => 'single',
            GD_TEMPLATE_TOPLEVEL_AUTHOR => 'author',
            // GD_TEMPLATE_TOPLEVEL_404 => '404',
        );
        $hierarchy = $hierarchies[$toplevel_template_id];
        if (!$hierarchy) {
            throw new Exception(sprintf('No Hierarchy for $template_id \'%s\' (%s)', $template_id, full_url()));
        }

        // Set the conditional hierarchy values in 'global-state' properly
        GD_TemplateManager_Utils::set_vars_hierarchy($hierarchy);

        // Save the list of all the paths. It will be needed later, to add the resources for the default tabs for 'single'
        $paths = array();

        if ($hierarchy == 'single') {

            // For all the posts passed, get the resources and place them under the path of the post, 
            // without including the post's slug itself (eg: mesym.com/en/posts/this-is-a-post/ will save
            // resources under key mesym.com/en/posts/)
            $home_url = trailingslashit(home_url());
            // if ($posts = $variables['posts']) {

            // foreach ($posts as $post_id) {
            foreach ($ids as $post_id) {

                $vars['global-state']['post'] = get_post($post_id);

                // For the single hierarchy, we must save the resources under the category path,
                // for all the categories in the website
                $path = trailingslashit(GD_TemplateManager_Utils::get_post_path($post_id, true));
                $paths[] = $path;

                self::add_resources_from_current_loop($resources[$path], $key, $toplevel_template_id, $merge);

                // We need to delete the cache, because PoP_VarsUtils::get_vars_identifier() doesn't have all the information needed
                // Eg: because the categories are not in $vars, it can't tell the difference between past and future events,
                // or from 2 posts with different category
                $gd_template_processor_runtimecache->delete_cache();
            }
            // }
        }
        elseif ($hierarchy == 'page') {

            $home_url = trailingslashit(home_url());

            // For the page hierarchy, we must save the resources under the page path,
            // for all pages in the website
            // $post = $variables['page'];
            foreach ($ids as $page_id) {

                $vars['global-state']['post'] = get_post($page_id);

                $path = trailingslashit(GD_TemplateManager_Utils::get_page_path($page_id));
                $paths[] = $path;
                
                // Calculate and save the resources
                $resources[$path][$key] = self::get_resources_from_current_vars($toplevel_template_id);

                // Reset the cache
                $gd_template_processor_runtimecache->delete_cache();
            }
        }
        elseif ($hierarchy == 'author') {

            // $authors = $variables['authors'];
            // foreach ($authors as $author) {
            foreach ($ids as $author) {
                
                $vars['global-state']['author'] = $author;
                self::add_resources_from_current_loop($resources, $key, $toplevel_template_id, $merge);

                // Reset the cache
                $gd_template_processor_runtimecache->delete_cache();
            }
        }
        elseif ($hierarchy == 'tag') {

            // // Commented, because there is no difference in configuration for any particular tag,
            // // so we never inquire the current tag for obtaining the configuration. So no need for this
            foreach ($ids as $tag_id) {
                
                $vars['global-state']['queried-object'] = get_tag($tag_id);
                $vars['global-state']['queried-object-id'] = $tag_id;
                self::add_resources_from_current_loop($resources, $key, $toplevel_template_id, $merge);

                // Reset the cache
                $gd_template_processor_runtimecache->delete_cache();
            }
        }
        elseif ($hierarchy == 'home') {
        
            // Calculate and save the resources
            $resources[$key] = self::get_resources_from_current_vars($toplevel_template_id);

            // Reset the cache
            $gd_template_processor_runtimecache->delete_cache();
        }

        $flat_hierarchies = array(
            'home', 
            'tag', 
            'author',
        );
        $path_hierarchies = array(
            'single', 
            'page',
        );

        // For hierarchies where can have a tab, if the tab is the default one, then also
        // add an entry without the tab (we can't add t:default in JS since we don't know which is the default tab for each hierarchy, just from the URL pattern)
        $notab_hierarchies = array(
            'author', 
            'single', 
            'tag',
        );

        $duplicate_notab = in_array($hierarchy, $notab_hierarchies) && $options['is-default-tab'];
        if ($duplicate_notab) {

            // Flat hierarchies: saved under $resources
            // Non-flat (eg: single): saved under $resources[$path] for each $path
            $notab_params = $params;
            array_splice($notab_params, array_search(POP_RESOURCELOADERIDENTIFIER_TAB.$tab, $notab_params), 1);
            $notab_key = implode(GD_SEPARATOR_RESOURCELOADER, $notab_params);

            if (in_array($hierarchy, $flat_hierarchies)) {

                $resources[$notab_key] = $resources[$key];
            }
            else {

                foreach ($paths as $path) {

                    $resources[$path][$notab_key] = $resources[$path][$key];
                }
            }
        }

        // If the format was among navigator, addons, etc, the link will actually not have the format parameter,
        // it will be default. So duplicate the entry, making it for the default also
        if ($duplicate_as_default_format) {

            $defaultformat_params = $params;
            $defaultformat_params[0] = POP_RESOURCELOADERIDENTIFIER_FORMAT.POP_VALUES_DEFAULT;
            $defaultformat_key = implode(GD_SEPARATOR_RESOURCELOADER, $defaultformat_params);

            if (in_array($hierarchy, $flat_hierarchies)) {

                $resources[$defaultformat_key] = $resources[$key];
            }
            elseif (in_array($hierarchy, $path_hierarchies)) {

                foreach ($paths as $path) {

                    $resources[$path][$defaultformat_key] = $resources[$path][$key];
                }
            }

            if ($duplicate_notab) {

                // If also duplicate, add the same entry without the tab
                $defaultformat_notab_params = $notab_params;
                $defaultformat_notab_params[0] = POP_RESOURCELOADERIDENTIFIER_FORMAT.POP_VALUES_DEFAULT;
                $defaultformat_notab_key = implode(GD_SEPARATOR_RESOURCELOADER, $defaultformat_notab_params);

                if (in_array($hierarchy, $flat_hierarchies)) {

                    $resources[$defaultformat_notab_key] = $resources[$key];
                }
                else {

                    foreach ($paths as $path) {

                        $resources[$path][$defaultformat_notab_key] = $resources[$path][$key];
                    }
                }
            }
        }

        // Restore $vars to its original values
        foreach ($vars_keys as $vars_key) {
            $vars[$vars_key] = $original_vars[$vars_key];
        }
        GD_TemplateManager_Utils::set_vars_hierarchy($vars['global-state']['hierarchy']);
        // $vars['output'] = $original_output;
        // $vars['fetching-json'] = $original_fetchingjson;
        // $vars['fetching-json-settingsdata'] = $original_fetchingjsonsettingsdata;
        // $vars['fetching-json-settings'] = $original_fetchingjsonsettings;
        // $vars['fetching-json-data'] = $original_fetchingjsondata;
        // $vars['format'] = $original_format;
        // $vars['tab'] = $original_tab;
        // $vars['target'] = $original_target;
        // GD_TemplateManager_Utils::set_vars_hierarchy($original_hierarchy);

        // Set the runtimecache once again to operate with $request
        $gd_template_processor_runtimecache->setUseVarsIdentifier(false);
    }

    protected static function add_resources_from_current_loop(&$resources, $key, $toplevel_template_id, $merge = false) {

        // Calculate and save the resources
        $item_resources = self::get_resources_from_current_vars($toplevel_template_id);
        if ($merge) {  

            // // The group is 'js', 'external', 'templates'
            // foreach ($item_resources as $group => $group_resources) {

            //     $resources[$key][$group] = $resources[$key][$group] ?? array();
            //     $resources[$key][$group] = /*array_values(*/array_unique(array_merge(
            //         $resources[$key][$group],
            //         $group_resources
            //     ))/*)*/;
            // }
            $resources[$key] = $resources[$key] ?? array();
            $resources[$key] = /*array_values(*/array_unique(array_merge(
                $resources[$key],
                $item_resources
            ))/*)*/;
        }
        else {

            $resources[$key] = $item_resources;
        }
    }

    protected static function get_resources_from_current_vars($toplevel_template_id) {
        
        global $gd_template_processor_manager;

        // Generate the $atts for this $vars
        $engine = PoP_Engine_Factory::get_instance();
        $toplevel_atts = $engine->get_atts($toplevel_template_id);//array();

        // We are given a toplevel. Iterate through all the pageSections, and obtain their resources
        $template_sources = $methods = array();
        $toplevel_processor = $gd_template_processor_manager->get_processor($toplevel_template_id);

        // Get the Handlebars list of resources needed for that pageSection
        $template_sources = array_values(array_unique(array_values($toplevel_processor->get_templates_sources($toplevel_template_id, $toplevel_atts))));
        $template_extra_sources = array_values(array_unique(array_flatten(array_values($toplevel_processor->get_templates_extra_sources($toplevel_template_id, $toplevel_atts)))));

        $sources = array_unique(array_merge(
            $template_sources,
            $template_extra_sources
        ));

        // Get the list of methods that will be called in that pageSection, to obtain, later on, what JS resources are needed 
        $methods = array_values(self::get_jsmethods_from_template($toplevel_template_id, $toplevel_atts));

        // Finally, merge all the template and JS resources together
        return self::calculate_resources($sources, $methods);
    }

    public static function get_jsmethods_from_template($toplevel_template_id, $toplevel_atts) {
        
        global $gd_template_processor_manager;
        $processor = $gd_template_processor_manager->get_processor($toplevel_template_id);
        $pageSectionJSMethods = $processor->get_pagesection_jsmethods($toplevel_template_id, $toplevel_atts);
        $blockJSMethods = $processor->get_block_jsmethods($toplevel_template_id, $toplevel_atts);

        return self::get_jsmethods($pageSectionJSMethods, $blockJSMethods);
    }

    public static function get_jsmethods($pageSectionJSMethods, $blockJSMethods) {
        
        global $pop_resourceloaderprocessor_manager;

        // Start with those methods that are always executed, already by the framework, not from configuration
        $js_methods = $pop_resourceloaderprocessor_manager->get_initial_jsmethods();

        // Add all the pageSection methods
        foreach ($pageSectionJSMethods as $pageSection => $methods) {
            self::add_pagesection_jsmethods($js_methods, $methods);
        }

        // Add all the block methods
        foreach ($blockJSMethods as $pageSection => $blockTemplateMethods) {
            foreach ($blockTemplateMethods as $template => $templateMethods) {
                self::add_block_jsmethods($js_methods, $templateMethods);
            }
        }

        return array_values(array_unique($js_methods));
    }

    public static function add_pagesection_jsmethods(&$js_methods, $templateMethods) {
        
        foreach ($templateMethods as $template => $groupMethods) {
            foreach ($groupMethods as $group => $methods) {
                foreach ($methods as $method) {
                    $js_methods[] = $method;
                }
            }
        }
    }

    public static function add_block_jsmethods(&$js_methods, $templateMethods) {
        
        if ($groupMethods = $templateMethods[GD_JS_METHODS]) {
            foreach ($groupMethods as $group => $methods) {
                foreach ($methods as $method) {
                    $js_methods[] = $method;
                }
            }
        }

        if ($templateMethods[GD_JS_NEXT]) {
            foreach ($templateMethods[GD_JS_NEXT] as $next) {
                self::add_block_jsmethods($js_methods, $next);
            }
        }
    }
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
// PoP_ResourceLoaderProcessorUtils::init();