<?php

use PoP\ComponentModel\Facades\Cache\TransientCacheManagerFacade;
use PoP\ComponentModel\Facades\Engine\EngineFacade;
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade;
use PoP\Root\Routing\RequestNature;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\CustomPosts\Routing\RequestNature as CustomPostRequestNature;
use PoPSchema\Pages\Facades\PageTypeAPIFacade;
use PoPSchema\Pages\Routing\PathUtils;
use PoPSchema\Pages\Routing\RequestNature as PageRequestNature;
use PoPSchema\PostTags\Facades\PostTagTypeAPIFacade;
use PoPSchema\Tags\Routing\RequestNature as TagRequestNature;
use PoPSchema\Users\Facades\UserTypeAPIFacade;
use PoPSchema\Users\Routing\RequestNature as UserRequestNature;

class PoP_ResourceLoaderProcessorUtils {

    public static function isLoadingSite($modulefilter) {

        return is_null($modulefilter);
    }

    public static function deleteEntries($delete_current_mapping) {

        // Get the already generated entries from the cache
        global $pop_resourceloader_mappingstoragemanager, $pop_resourceloaderprocessor_state;
        $pop_resourceloader_mappingstoragemanager->delete($delete_current_mapping);
        $pop_resourceloaderprocessor_state->deleteEntries();
    }

    public static function saveEntries() {

        global $pop_resourceloaderprocessor_state;
        $pop_resourceloaderprocessor_state->saveEntries();
    }

    function getBundleId($resources, $addRandom) {

        global $pop_resourceloaderprocessor_state;
        return $pop_resourceloaderprocessor_state->getBundleId($resources, $addRandom);
    }

    function getBundlegroupId($resourcebundles, $addRandom) {

        global $pop_resourceloaderprocessor_state;
        return $pop_resourceloaderprocessor_state->getBundlegroupId($resourcebundles, $addRandom);
    }

    function getKeyId($key) {

        global $pop_resourceloaderprocessor_state;
        return $pop_resourceloaderprocessor_state->getKeyId($key);
    }

    function getBundleVersion($bundleId) {

        global $pop_resourceloaderprocessor_state;
        return $pop_resourceloaderprocessor_state->getBundleVersion($bundleId);
    }

    function getBundlegroupVersion($bundleGroupId) {

        global $pop_resourceloaderprocessor_state;
        return $pop_resourceloaderprocessor_state->getBundlegroupVersion($bundleGroupId);
    }

    function setBundleVersion($bundleId, $version) {

        global $pop_resourceloaderprocessor_state;
        return $pop_resourceloaderprocessor_state->setBundleVersion($bundleId, $version);
    }

    function setBundlegroupVersion($bundleGroupId, $version) {

        global $pop_resourceloaderprocessor_state;
        return $pop_resourceloaderprocessor_state->setBundlegroupVersion($bundleGroupId, $version);
    }

    public static function getNoconflictResourceName($resourceName) {

        // Add 'pop-' before the registered name, to avoid conflicts with external parties (eg: WP also registers script "utils")
        return 'pop-'.$resourceName;
    }

    public static function getHandle(array $resource) {

        global $pop_resourceloaderprocessor_manager;
        return $pop_resourceloaderprocessor_manager->getProcessor($resource)->getHandle($resource);
    }

    public static function chunkResources($resources) {

        // Further divide each array into chunks, to maximize the possibilities of different pages sharing the same bundles
        $chunk_size = PoP_ResourceLoader_ServerUtils::getBundlesChunkSize();
        return array_chunk($resources, $chunk_size);
    }

    public static function getNatureRoutesAndFormats() {

        $route_formats = array();

        $settingsprocessor_manager = \PoP\ComponentModel\Settings\SettingsProcessorManagerFactory::getInstance();
        $pop_module_routemoduleprocessor_manager = RouteModuleProcessorManagerFacade::getInstance();
        $routemoduleprocessors = $pop_module_routemoduleprocessor_manager->getProcessors(POP_PAGEMODULEGROUPPLACEHOLDER_MAINCONTENTMODULE);
        foreach ($routemoduleprocessors as $routemoduleprocessor) {
            foreach ($routemoduleprocessor->getModulesVarsPropertiesByNatureAndRoute() as $nature => $route_vars_properties) {
                foreach ($route_vars_properties as $route => $vars_properties) {

                    // "false" routes may be added to the configuration when that route must not be installed. Check for this case and skip it
                    if (!$route) {
                        continue;
                    }

                    // If this page is for internal use (eg: System Build/Generate/Install), then do not print out in the configuration
                    // Users should not be made aware of this path!
                    $settingsprocessor = $settingsprocessor_manager->getProcessor($route);
                    $internals = $settingsprocessor->isForInternalUse();
                    if ($internals && $internals[$route]) {
                        continue;
                    }

                    $route_formats[$nature][$route] = $route_formats[$nature][$route] ?? [];
                    foreach ($vars_properties as $vars_properties_set) {
                        $conditions = $vars_properties_set['conditions'];
                        // Add the format under the return variable
                        $format = $conditions['format'] ?? \PoP\ConfigurationComponentModel\Constants\Values::DEFAULT;
                        if (!in_array($format, $route_formats[$nature][$route])) {
                            $route_formats[$nature][$route][] = $format;
                        }
                    }
                }
            }
        }
        return $route_formats;
    }

    public static function addResourcesFromSettingsprocessors($modulefilter, &$resources, $nature, $ids = array(), $merge = false, $options = array()) {

        // Keep the original values in the $vars, since they'll need to be changed to pretend we are in a different $request
        $vars = &ApplicationState::$vars;

        // Iterate through all the pages added as configuration for this nature,
        // and all the resources for each
        $nature_route_formats = self::getNatureRoutesAndFormats();
        if ($route_formats = $nature_route_formats[$nature] ?? null) {

            // If there is more than one page, then add the tabs component (eg: feeds)
            // If there is only one page defined, then there is no need for the tabs (eg: homepage)
            // $add_tabs = ($nature == RequestNature::GENERIC/*PageRequestNature::PAGE*/) ? false : count($route_formats) > 1;
            $add_tabs = count($route_formats) > 1;
            foreach ($route_formats as $route => $formats) {

                foreach ($formats as $format) {

                    $item_options = $options;
                    $components = array(
                        'format' => $format,
                    );
                    $original_layouts = array();
                    if ($nature == RequestNature::GENERIC/*PageRequestNature::PAGE*/) {

                        $ids = array(
                            $route,
                        );
                    }
                    if ($add_tabs) {

                        $components['route'] = $route;

                        // If this tab is the default one, an entry with no tab must also be created
                        // if ($route == RequestUtils::getNatureDefaultPage($nature)) {
                        if ($format == \PoP\ConfigurationComponentModel\Constants\Values::DEFAULT) {
                            $item_options['is-default-route'] = true;
                        }
                    }
                    self::addResourcesFromCurrentVars($modulefilter, $resources, $nature, $ids, $merge, $components, $item_options);

                    // Restore the original $vars['layouts']
                    if ($original_layouts) {

                        $vars['layouts'] = $original_layouts;
                    }
                }
            }
        }
    }

    public static function calculateResources($modulefilter, $template_resources, $critical_methods, $noncritical_methods, $modules_resources, $model_instance_id, $options = array()) {

        global $pop_jsresourceloaderprocessor_manager;

        $resources = array();

        // Make sure there are no duplicates
        $critical_methods = array_unique($critical_methods);
        $noncritical_methods = array_unique($noncritical_methods);

        // Add all the JS dependencies from the templates, and the templates themselves
        foreach ($template_resources as $template_resource) {

            $pop_jsresourceloaderprocessor_manager->addResourceDependencies($resources, $template_resource, true);
        }
        $critical_resources = $noncritical_resources = array();

        // Add the initial resources only when doing "loading-site". When doing "fetching-json" no need, since those assets will have been already loaded by then
        $loadingSite = self::isLoadingSite($modulefilter);
        $pop_jsresourceloaderprocessor_manager->addResourcesFromJsmethods($critical_resources, $critical_methods, $template_resources, $loadingSite);

        // Add the dependencies for the template resources also
        foreach ($template_resources as $template_resource) {
            $pop_jsresourceloaderprocessor_manager->addResourceDependencies($critical_resources, $template_resource, false);
        }
        $pop_jsresourceloaderprocessor_manager->addResourcesFromJsmethods($noncritical_resources, $noncritical_methods, array(), false);

        // If a resource is both critical and non-critical, then remove it from non-critical
        $noncritical_resources = array_values(array_diff(
            $noncritical_resources,
            $critical_resources
        ));

        // Save the $noncritical_resources internally, so it can be used to set resources as "defer"
        // To store them, the $model_instance_id must be passed as a parameter, because then it can be uses when generating
        // bundle(group)s, which are calculated all at the beginning, and created all later together; if we don't
        // keep the $model_instance_id, we don't know what non-critical resources belong to which generation process
        if ($noncritical_resources) {
            $memorymanager = TransientCacheManagerFacade::getInstance();
            $memorymanager->storeComponentModelCache($model_instance_id, POP_MEMORYTYPE_NONCRITICALRESOURCES, $noncritical_resources);
        }

        $resources = array_values(
            array_unique(
                array_merge(
                    $resources,
                    $critical_resources,
                    $noncritical_resources,
                    $modules_resources
                ),
                SORT_REGULAR
            )
        );

        return $resources;
    }

    protected static function setExtraVarsProperties(&$vars, $extra_vars, $id) {

        foreach ($extra_vars as $extra_var => $extra_var_id_value) {

            if (!is_null($extra_var_id_value[$id])) {

                $vars[$extra_var] = $extra_var_id_value[$id];
            }
        }
    }

    public static function addResourcesFromCurrentVars($modulefilter, &$resources, $nature, $ids = array(), $merge = false, $components = array(), $options = array()) {

        // Keep the original values in the $vars, since they'll need to be changed
        // to pretend we are in a different $request
        $vars = &ApplicationState::$vars;
        $userTypeAPI = UserTypeAPIFacade::getInstance();
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $pageTypeAPI = PageTypeAPIFacade::getInstance();
        $postTagTypeAPI = PostTagTypeAPIFacade::getInstance();

        // Comment Leo 11/11/2017: we can only do $merge = true when doing "fetching-json",
        // because we need to bundle all resources for all different cases for the same URL
        // However, if doing "loading-site", then we can't bundle all the cases together,
        // or we will not be able to get back the specific bundle(group)s for the currently visited request
        // (this is the case when doing
        // PoP_ResourceLoader_ServerUtils::getEnqueuefileType() == 'bundle' or 'bundlegroup')
        $loadingSite = self::isLoadingSite($modulefilter);
        if ($loadingSite) {
            $merge = false;
        }

        // IMPORTANT: we must pretend it's 'fetching-page' request,
        // so that it doesn't load the frame files once again,
        // which will be already loaded (PRPL is triggered when clicking
        // on any link => will always be doing ?output=json)
        $original_vars = array();
        $extra_vars = $options['extra-vars'] ?? array();
        $vars_keys = array_merge(
            array(
                'nature',
                'output',
                'modulefilter',
                // Variables over which the composition of different blocks depends
                'loading-site',
                'fetching-site',
                'format',
                'route',
                'target',
                'dataoutputitems',
                'datasourceselector',
                // Nature
                'routing',
            ),
            array_unique(array_keys($extra_vars))
        );
        foreach ($vars_keys as $vars_key) {
            $original_vars[$vars_key] = $vars[$vars_key];
        }

        // Obtain the key under which to add the resources, which is a combination of components 'format', 'route' and 'target'
        // This code is replicated in function `loadResources` in resourceloader.js
        $params = array();
        $format = $components['format'] ?? ($loadingSite ? '' : \PoP\ConfigurationComponentModel\Constants\Values::DEFAULT);
        $route = $components['route'];

        // Targets special cases: certain formats (eg: Navigator) are used only from a corresponding target
        // So if we have that format, use the correponding target, or if not, the default is main
        // Give priority to $components['target'] though, so if we set this value, then it will use that value
        // Then, when processing POP_ADDLOCATIONS_ROUTE_ADDLOCATION, we can have a configuration for both target=main and target=modals
        // Then, also set the format as the "default" one, because these pages will never be called using format="navigator" (etc), there will be no format whatsoever
        $duplicate_as_default_format = false;
        if ($components['target'] ?? null) {
            $target = $components['target'];
        } else {
            $format_targets = \PoP\Root\App::applyFilters(
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
                // $format = \PoP\ConfigurationComponentModel\Constants\Values::DEFAULT;
            } else {
                $target = \PoP\ConfigurationComponentModel\Constants\Targets::MAIN;
            }
        }

        // If doing loadingSite, then the page must only hold its own resources, and be stored under its own, unique key
        // Then, resources for author => Individual/Organization must NOT be bundled together
        // If doing JSON, then the key is the combination of the format/tab/target
        // Then, resources for author => Individual/Organization must be bundled together
        if (!$loadingSite) {
            $params[] = POP_RESOURCELOADERIDENTIFIER_FORMAT.$format;
            if ($route) {
                $params[] = POP_RESOURCELOADERIDENTIFIER_ROUTE.$route;
            }
            $params[] = POP_RESOURCELOADERIDENTIFIER_TARGET.$target;

            $key = implode(GD_SEPARATOR_RESOURCELOADER, $params);
        }

        // Pretend we are in that intended page, by setting the $vars in accordance
        // Comment Leo 07/11/2017: allow to have both $fetching_page and $loadingSite,
        // the latter one is needed for enqueuing bundles/bundlegroups instead of
        // resources when first loading the website
        if ($loadingSite) {
            $vars['output'] = \PoP\ComponentModel\Constants\Outputs::HTML;
            $vars['modulefilter'] = null;
            $vars['loading-site'] = true;
            $vars['fetching-site'] = true;
        } else {
            $vars['output'] = \PoP\ComponentModel\Constants\Outputs::JSON;
            $vars['modulefilter'] = $modulefilter;
            $vars['loading-site'] = false;
            $vars['fetching-site'] = false;
        }
        $vars['nature'] = $nature;
        $vars['dataoutputitems'] = array(
            \PoP\ComponentModel\Constants\DataOutputItems::META,
            \PoP\ConfigurationComponentModel\Constants\DataOutputItems::MODULESETTINGS,
            \PoP\ComponentModel\Constants\DataOutputItems::MODULE_DATA,
            \PoP\ComponentModel\Constants\DataOutputItems::DATABASES,
            \PoP\ComponentModel\Constants\DataOutputItems::SESSION,
        );
        $vars['datasourceselector'] = \PoP\ComponentModel\Constants\DataSourceSelectors::MODELANDREQUEST;
        $vars['format'] = $format;
        $vars['route'] = $route;
        $vars['target'] = $target;
        // $vars['routing'] = array();
        // ApplicationState::setNatureInGlobalState();

        // Save the list of all the paths. It will be needed later,
        // to add the resources for the default tabs for 'single'
        $paths = array();

        if ($nature == PageRequestNature::PAGE) {
            // For the page nature, we must save the resources under the page path,
            // for all pages in the website
            foreach ($ids as $page_id) {
                // Allow to set the extra vars
                self::setExtraVarsProperties($vars, $extra_vars, $page_id);

                $vars['routing'] = [];
                $vars['routing']['queried-object'] = $pageTypeAPI->getPage($page_id);
                $vars['routing']['queried-object-id'] = $page_id;
                ApplicationState::augmentVarsProperties();

                // If doing loadingSite, then the page must only hold its own resources,
                // and be stored under its own, unique key
                // Then, resources for author => Individual/Organization must NOT be bundled together
                if ($loadingSite) {
                    $key = \PoP\ComponentModel\Facades\ModelInstance\ModelInstanceFacade::getInstance()->getModelInstanceId();
                }

                $path = GeneralUtils::maybeAddTrailingSlash(PathUtils::getPagePath($page_id));
                $paths[] = $path;

                // Calculate and save the resources
                $resources[$path][$key] = self::getResourcesFromCurrentVars($modulefilter, $options);

                // // Reset the cache
                // $pop_module_processor_runtimecache->deleteCache();
            }
        } elseif ($nature == RequestNature::GENERIC) {

            $vars['routing'] = [];
            ApplicationState::augmentVarsProperties();

            // For the page nature, we must save the resources under the page path,
            // for all pages in the website
            foreach ($ids as $route) {

                // Allow to set the extra vars
                self::setExtraVarsProperties($vars, $extra_vars, $route);

                $current_route = $vars['route'];
                $vars['route'] = $route;

                // If doing loadingSite, then the page must only hold its own resources, and be stored under its own, unique key
                // Then, resources for author => Individual/Organization must NOT be bundled together
                if ($loadingSite) {

                    $key = \PoP\ComponentModel\Facades\ModelInstance\ModelInstanceFacade::getInstance()->getModelInstanceId();
                }

                $path = $route.'/';
                $paths[] = $path;

                // Calculate and save the resources
                $resources[$path][$key] = self::getResourcesFromCurrentVars($modulefilter, $options);

                $vars['route'] = $current_route;

                // // Reset the cache
                // $pop_module_processor_runtimecache->deleteCache();
            }
        } elseif ($nature == CustomPostRequestNature::CUSTOMPOST) {

            foreach ($ids as $post_id) {

                // Allow to set the extra vars
                self::setExtraVarsProperties($vars, $extra_vars, $post_id);

                $vars['routing'] = [];
                $vars['routing']['queried-object'] = $customPostTypeAPI->getCustomPost($post_id);
                $vars['routing']['queried-object-id'] = $post_id;
                ApplicationState::augmentVarsProperties();

                // If doing loadingSite, then the page must only hold its own resources, and be stored under its own, unique key
                // Then, resources for author => Individual/Organization must NOT be bundled together
                if ($loadingSite) {

                    $key = \PoP\ComponentModel\Facades\ModelInstance\ModelInstanceFacade::getInstance()->getModelInstanceId();
                }

                // For the single nature, we must save the resources under the category path,
                // for all the categories in the website
                $path = GeneralUtils::maybeAddTrailingSlash(\PoPSchema\Posts\Engine_Utils::getCustomPostPath($post_id, true));
                $paths[] = $path;

                self::addResourcesFromCurrentLoop($modulefilter, $resources[$path], $key, $merge, $options);

                // // We need to delete the cache, because PoP_VarsUtils::getModelInstanceComponentsFromAppState() doesn't have all the information needed
                // // Eg: because the categories are not in $vars, it can't tell the difference between past and future events,
                // // or from 2 posts with different category
                // $pop_module_processor_runtimecache->deleteCache();
            }
        } elseif ($nature == UserRequestNature::USER) {

            foreach ($ids as $author) {

                // Allow to set the extra vars: "source" => "community"/"organization", with the value set under the author id
                self::setExtraVarsProperties($vars, $extra_vars, $author);

                $vars['routing'] = [];
                $vars['routing']['queried-object'] = $userTypeAPI->getUserById($author);
                $vars['routing']['queried-object-id'] = $author;
                ApplicationState::augmentVarsProperties();

                // If doing loadingSite, then the page must only hold its own resources, and be stored under its own, unique key
                // Then, resources for author => Individual/Organization must NOT be bundled together
                if ($loadingSite) {

                    $key = \PoP\ComponentModel\Facades\ModelInstance\ModelInstanceFacade::getInstance()->getModelInstanceId();
                }

                self::addResourcesFromCurrentLoop($modulefilter, $resources, $key, $merge, $options);

                // // Reset the cache
                // $pop_module_processor_runtimecache->deleteCache();
            }
        } elseif ($nature == TagRequestNature::TAG) {

            // // Commented, because there is no difference in configuration for any particular tag,
            // // so we never inquire the current tag for obtaining the configuration. So no need for this
            foreach ($ids as $tag_id) {

                // Allow to set the extra vars
                self::setExtraVarsProperties($vars, $extra_vars, $tag_id);

                $vars['routing'] = [];
                $vars['routing']['queried-object'] = $postTagTypeAPI->getTag($tag_id);
                $vars['routing']['queried-object-id'] = $tag_id;
                ApplicationState::augmentVarsProperties();

                // If doing loadingSite, then the page must only hold its own resources, and be stored under its own, unique key
                // Then, resources for author => Individual/Organization must NOT be bundled together
                if ($loadingSite) {

                    $key = \PoP\ComponentModel\Facades\ModelInstance\ModelInstanceFacade::getInstance()->getModelInstanceId();
                }

                self::addResourcesFromCurrentLoop($modulefilter, $resources, $key, $merge, $options);

                // // Reset the cache
                // $pop_module_processor_runtimecache->deleteCache();
            }
        } elseif ($nature == RequestNature::HOME) {

            $vars['routing'] = [];
            ApplicationState::augmentVarsProperties();

            // If doing loadingSite, then the page must only hold its own resources, and be stored under its own, unique key
            // Then, resources for author => Individual/Organization must NOT be bundled together
            if ($loadingSite) {

                $key = \PoP\ComponentModel\Facades\ModelInstance\ModelInstanceFacade::getInstance()->getModelInstanceId();
            }

            // Calculate and save the resources
            $resources[$key] = self::getResourcesFromCurrentVars($modulefilter, $options);

            // // Reset the cache
            // $pop_module_processor_runtimecache->deleteCache();
        } elseif ($nature == RequestNature::NOTFOUND) {

            $vars['routing'] = [];
            ApplicationState::augmentVarsProperties();

            // If doing loadingSite, then the page must only hold its own resources, and be stored under its own, unique key
            // Then, resources for author => Individual/Organization must NOT be bundled together
            if ($loadingSite) {

                $key = \PoP\ComponentModel\Facades\ModelInstance\ModelInstanceFacade::getInstance()->getModelInstanceId();
            }

            // Calculate and save the resources
            $resources[$key] = self::getResourcesFromCurrentVars($modulefilter, $options);

            // // Reset the cache
            // $pop_module_processor_runtimecache->deleteCache();
        }

        // If doing JSON, then we may need to duplicate entries.
        // For loadingSite, no need
        if (!$loadingSite) {

            $flat_natures = array(
                RequestNature::HOME,
                TagRequestNature::TAG,
                UserRequestNature::USER,
            );
            $path_natures = array(
                CustomPostRequestNature::CUSTOMPOST,
                PageRequestNature::PAGE,
                RequestNature::GENERIC,
            );

            // For natures where can have a tab, if the tab is the default one, then also
            // add an entry without the tab (we can't add t:default in JS since we don't know which is the default tab for each nature, just from the URL pattern)
            $noroute_natures = array(
                // Comment Leo 10/04/2019: since switching from page to route, only routes cannot have a tab
                // UserRequestNature::USER,
                // CustomPostRequestNature::CUSTOMPOST,
                // TagRequestNature::TAG,
                RequestNature::GENERIC,
            );

            $duplicate_noroute = in_array($nature, $noroute_natures) && $options['is-default-route'];
            if ($duplicate_noroute) {

                // Flat natures: saved under $resources
                // Non-flat (eg: single): saved under $resources[$path] for each $path
                $noroute_params = $params;
                array_splice($noroute_params, array_search(POP_RESOURCELOADERIDENTIFIER_ROUTE.$route, $noroute_params), 1);
                $noroute_key = implode(GD_SEPARATOR_RESOURCELOADER, $noroute_params);

                if (in_array($nature, $flat_natures)) {

                    $resources[$noroute_key] = $resources[$key];
                }
                else {

                    foreach ($paths as $path) {

                        $resources[$path][$noroute_key] = $resources[$path][$key];
                    }
                }
            }

            // If the format was among navigator, addons, etc, the link will actually not have the format parameter,
            // it will be default. So duplicate the entry, making it for the default also
            if ($duplicate_as_default_format) {

                $defaultformat_params = $params;
                $defaultformat_params[0] = POP_RESOURCELOADERIDENTIFIER_FORMAT.\PoP\ConfigurationComponentModel\Constants\Values::DEFAULT;
                $defaultformat_key = implode(GD_SEPARATOR_RESOURCELOADER, $defaultformat_params);

                if (in_array($nature, $flat_natures)) {

                    $resources[$defaultformat_key] = $resources[$key];
                }
                elseif (in_array($nature, $path_natures)) {

                    foreach ($paths as $path) {

                        $resources[$path][$defaultformat_key] = $resources[$path][$key];
                    }
                }

                if ($duplicate_noroute) {

                    // If also duplicate, add the same entry without the tab
                    $defaultformat_noroute_params = $noroute_params;
                    $defaultformat_noroute_params[0] = POP_RESOURCELOADERIDENTIFIER_FORMAT.\PoP\ConfigurationComponentModel\Constants\Values::DEFAULT;
                    $defaultformat_noroute_key = implode(GD_SEPARATOR_RESOURCELOADER, $defaultformat_noroute_params);

                    if (in_array($nature, $flat_natures)) {

                        $resources[$defaultformat_noroute_key] = $resources[$key];
                    }
                    else {

                        foreach ($paths as $path) {

                            $resources[$path][$defaultformat_noroute_key] = $resources[$path][$key];
                        }
                    }
                }
            }
        }

        // Restore $vars to its original values
        foreach ($vars_keys as $vars_key) {
            $vars[$vars_key] = $original_vars[$vars_key];
        }
        ApplicationState::augmentVarsProperties();

        // // Set the runtimecache once again to operate with $request
        // $pop_module_processor_runtimecache->setUseVarsIdentifier(false);
    }

    protected static function addResourcesFromCurrentLoop($modulefilter, &$resources, $key, $merge = false, $options = array()) {

        // Calculate and save the resources
        $item_resources = self::getResourcesFromCurrentVars($modulefilter, $options);
        if ($merge) {

            $resources[$key] = $resources[$key] ?? array();
            $resources[$key] = array_unique(
                array_merge(
                    $resources[$key],
                    $item_resources
                ),
                SORT_REGULAR
            );
        }
        else {

            $resources[$key] = $item_resources;
        }
    }

    public static function getResourcesFromCurrentVars($modulefilter, $options = array()) {

        global $pop_resourcemoduledecoratorprocessor_manager;
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        // Get the current model_instance_id where to store $noncritical_resources
        $model_instance_id = \PoP\ComponentModel\Facades\ModelInstance\ModelInstanceFacade::getInstance()->getModelInstanceId();

        // Generate the $props for this $vars, or re-use the already-calculated one from the current execution (for when generating bundle(group) files on runtime)
        $engine = EngineFacade::getInstance();
        // After setting the new $vars properties, we can obtain the entry module
        $entryModule = $engine->getEntryModule();
        if ($options['use-engine-entrymodule-props'] ?? null) {
            $entry_model_props = $engine->model_props;
        } else {
            // To calculate all the resources below, we just need the static props.
            // Functions below should NOT rely on mutableonrequest props, or otherwise 2 posts may produce different resources,
            // and then visiting the 2nd post will not have its needed resource loaded
            $entry_model_props = $engine->getModelPropsModuletree($entryModule);
        }

        // We are given a toplevel. Iterate through all the pageSections, and obtain their resources
        $methods = array();
        $entry_processor = $moduleprocessor_manager->getProcessor($entryModule);
        $entry_processorresourcedecorator = $pop_resourcemoduledecoratorprocessor_manager->getProcessorDecorator($entry_processor);

        // Get the Handlebars list of resources needed for that pageSection
        $templateResources = $entry_processor->getTemplateResourcesMergedmoduletree($entryModule, $entry_model_props);

        // We also need to get the dynamic-templates and save it on the vars cache.
        // It will be needed from there when doing `function isDefer(array $resource, $model_instance_id)`
        if ($dynamic_template_resources = $entry_processorresourcedecorator->getDynamicTemplateResourcesMergedmoduletree($entryModule, $entry_model_props)) {
            $memorymanager = TransientCacheManagerFacade::getInstance();
            $memorymanager->storeComponentModelCache($model_instance_id, POP_MEMORYTYPE_DYNAMICTEMPLATERESOURCES, $dynamic_template_resources);
        }

        // Get the initial methods only if doing "loading-site"
        // Get the list of methods that will be called in that pageSection, to obtain, later on, what JS resources are needed
        // Comment Leo 21/11/2017: when switching from all methods to critical/noncritical ones, I dropped the array_values() out from $methods,
        // and added it when calculating $(non)critical_methods
        $loadingSite = self::isLoadingSite($modulefilter);
        $methods = self::getJsmethodsFromModule($loadingSite, $entryModule, $entry_model_props);
        $critical_methods = array_values($methods[POP_PROGRESSIVEBOOTING_CRITICAL]);
        $noncritical_methods = array_values($methods[POP_PROGRESSIVEBOOTING_NONCRITICAL]);

        // Get all the resources the module is dependent on. Eg: inline CSS styles
        // $modules_resources = array_values(array_unique(arrayFlatten(array_values($entry_processorresourcedecorator->getModulesResources($entryModule, $entry_model_props)))));
        $modules_resources = $entry_processorresourcedecorator->getResourcesMergedmoduletree($entryModule, $entry_model_props);

        // Finally, merge all the template and JS resources together
        return self::calculateResources($modulefilter, $templateResources, $critical_methods, $noncritical_methods, $modules_resources, $model_instance_id, $options);
    }

    public static function getJsmethodsFromModule($addInitial, $entryModule, $entry_model_props) {

        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
        $processor = $moduleprocessor_manager->getProcessor($entryModule);
        $pageSectionJSMethods = $processor->getPagesectionJsmethods($entryModule, $entry_model_props);
        $blockJSMethods = $processor->getJsmethodsModuletree($entryModule, $entry_model_props);
        return self::getJsmethods($pageSectionJSMethods, $blockJSMethods, $addInitial);
    }

    public static function getJsmethods($pageSectionJSMethods, $blockJSMethods, $addInitial = true) {

        global $pop_jsresourceloaderprocessor_manager;

        $critical_js_methods = array();
        $noncritical_js_methods = array();

        // Start with those methods that are always executed, already by the framework, not from configuration
        if ($addInitial) {
            $critical_js_methods = $pop_jsresourceloaderprocessor_manager->getInitialJsmethods();
        }

        // Add all the pageSection methods
        if ($pageSectionJSMethods) {
            foreach ($pageSectionJSMethods as $pageSection => $methods) {
                self::addPagesectionJsmethods($critical_js_methods, $methods, POP_PROGRESSIVEBOOTING_CRITICAL);
                self::addPagesectionJsmethods($noncritical_js_methods, $methods, POP_PROGRESSIVEBOOTING_NONCRITICAL);
            }
        }

        // Add all the block methods
        if ($blockJSMethods) {
            foreach ($blockJSMethods as $pageSection => $blockModuleMethods) {
                foreach ($blockModuleMethods as $module => $moduleMethods) {
                    self::addBlockJsmethods($critical_js_methods, $moduleMethods, POP_PROGRESSIVEBOOTING_CRITICAL);
                    self::addBlockJsmethods($noncritical_js_methods, $moduleMethods, POP_PROGRESSIVEBOOTING_NONCRITICAL);
                }
            }
        }

        $critical_js_methods = array_values(array_unique($critical_js_methods));
        $noncritical_js_methods = array_values(array_unique($noncritical_js_methods));

        // If a method was marked both critical and non-critical, then mark it as critical only
        $noncritical_js_methods = array_values(array_diff(
            $noncritical_js_methods,
            $critical_js_methods
        ));

        return array(
            POP_PROGRESSIVEBOOTING_CRITICAL => $critical_js_methods,
            POP_PROGRESSIVEBOOTING_NONCRITICAL => $noncritical_js_methods,
        );
    }

    public static function addPagesectionJsmethods(&$js_methods, $moduleMethods, $priority) {

        foreach ($moduleMethods as $module => $priorityGroupMethods) {
            if ($groupMethods = $priorityGroupMethods[$priority] ?? null) {
                foreach ($groupMethods as $group => $methods) {
                    foreach ($methods as $method) {
                        $js_methods[] = $method;
                    }
                }
            }
        }
    }

    public static function addBlockJsmethods(&$js_methods, $moduleMethods, $priority) {

        if ($priorityGroupMethods = $moduleMethods[GD_JS_METHODS]) {
            if ($groupMethods = $priorityGroupMethods[$priority] ?? null) {
                foreach ($groupMethods as $group => $methods) {
                    foreach ($methods as $method) {
                        $js_methods[] = $method;
                    }
                }
            }
        }

        if ($moduleMethods[GD_JS_NEXT]) {
            foreach ($moduleMethods[GD_JS_NEXT] as $next) {
                self::addBlockJsmethods($js_methods, $next, $priority);
            }
        }
    }
}
