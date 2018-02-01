<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ResourceLoader_FileReproduction_ResourcesConfigBase extends PoP_Engine_FileReproductionBase {

    protected function get_options() {
        
        return array(
            'match-paths' => $this->match_paths(),
            'match-hierarchy' => $this->match_hierarchy(),
            'match-format' => $this->match_format(),
        );
    }

    public function get_configuration() {
        
        $configuration = parent::get_configuration();

        // Domain
        $configuration['$domain'] = get_site_url();

        // Get all the resources, for the different hierarchies
        // Notice that we get the callback to filter results from the instantiating class
        $options = $this->get_options();
        $resource_mapping = PoP_ResourceLoader_FileReproduction_Utils::get_resource_mapping(true, $options);

        // Print the resource mapping information into the config file's configuration
        $configuration['$matchPaths'] = $this->match_paths();
        $configuration['$matchHierarchy'] = $this->match_hierarchy();
        $configuration['$matchFormat'] = $this->match_format();

        $configuration['$keys'] = $resource_mapping['keys'];
        $configuration['$sources'] = $resource_mapping['sources'];
        $configuration['$types'] = $resource_mapping['types'];
        $configuration['$orderedLoadResources'] = $resource_mapping['ordered-load-resources'];

        // We can't do array_merge for bundles and bundlegroups, or their $bundleId/$bundleGroupId, which is the key of each
        // array, will be lost. Then, use the "+" operator, which preserves keys
        // Important: The keys in 'js' and 'css' will NEVER overlap! Because when generating the $bundleId/$bundleGroupId, we call
        // the same process get_bundle_id($resources)/get_bundlegroup_id($resources), being $resources different 
        // (in one case, only .js files, in the other, only .css files)
        $configuration['$bundles'] = 
            $resource_mapping['bundles'][POP_RESOURCELOADER_RESOURCETYPE_JS][POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL] +
            $resource_mapping['bundles'][POP_RESOURCELOADER_RESOURCETYPE_JS][POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR] +
            $resource_mapping['bundles'][POP_RESOURCELOADER_RESOURCETYPE_JS][POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC] +
            $resource_mapping['bundles'][POP_RESOURCELOADER_RESOURCETYPE_JS][POP_RESOURCELOADER_RESOURCESUBTYPE_TEMPLATE] +
            $resource_mapping['bundles'][POP_RESOURCELOADER_RESOURCETYPE_CSS][POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL] +
            $resource_mapping['bundles'][POP_RESOURCELOADER_RESOURCETYPE_CSS][POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR] +
            $resource_mapping['bundles'][POP_RESOURCELOADER_RESOURCETYPE_CSS][POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC];
        $configuration['$bundleGroups'] = 
            $resource_mapping['bundle-groups'][POP_RESOURCELOADER_RESOURCETYPE_JS][POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL] +
            $resource_mapping['bundle-groups'][POP_RESOURCELOADER_RESOURCETYPE_JS][POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR] +
            $resource_mapping['bundle-groups'][POP_RESOURCELOADER_RESOURCETYPE_JS][POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC] +
            $resource_mapping['bundle-groups'][POP_RESOURCELOADER_RESOURCETYPE_JS][POP_RESOURCELOADER_RESOURCESUBTYPE_TEMPLATE] +
            $resource_mapping['bundle-groups'][POP_RESOURCELOADER_RESOURCETYPE_CSS][POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL] +
            $resource_mapping['bundle-groups'][POP_RESOURCELOADER_RESOURCETYPE_CSS][POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR] +
            $resource_mapping['bundle-groups'][POP_RESOURCELOADER_RESOURCETYPE_CSS][POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC];

        // Merge all the resources together. Because the same $keys appear for both CSS and JS resources, we can't just do an array_merge(),
        // or otherwise CSS values will override JS. Then, iterate all the keys, and merge their values
        $merged_resources = array();
        $types = array(
            POP_RESOURCELOADER_RESOURCETYPE_JS => array(
                POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL,
                POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR,
                POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC,
                POP_RESOURCELOADER_RESOURCESUBTYPE_TEMPLATE,
            ),
            POP_RESOURCELOADER_RESOURCETYPE_CSS => array(
                POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL,
                POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR,
                POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC,
            ),
        );
        foreach ($types as $type => $subtypes) {
            foreach ($subtypes as $subtype) {
                foreach ($resource_mapping['resources'][$type][$subtype]['flat'] as $hierarchy => $key_bundlegroups) {
                    foreach ($key_bundlegroups as $keyId => $bundleGroupIds) {
                        $merged_resources[$hierarchy][$keyId] = $merged_resources[$hierarchy][$keyId] ?? array();
                        $merged_resources[$hierarchy][$keyId] = array_merge(
                            $merged_resources[$hierarchy][$keyId],
                            $bundleGroupIds
                        );
                    }
                }
                foreach ($resource_mapping['resources'][$type][$subtype]['path'] as $hierarchy => $path_key_bundlegroup) {
                    foreach ($path_key_bundlegroup as $path => $key_bundlegroups) {
                        foreach ($key_bundlegroups as $keyId => $bundleGroupIds) {
                            $merged_resources[$hierarchy][$path][$keyId] = $merged_resources[$hierarchy][$path][$keyId] ?? array();
                            $merged_resources[$hierarchy][$path][$keyId] = array_merge(
                                $merged_resources[$hierarchy][$path][$keyId],
                                $bundleGroupIds
                            );
                        }
                    }
                }
            }
        }
        $configuration['$resources'] = $merged_resources;

        return $configuration;
    }

    protected function match_paths() {
        
        return array();
    }

    protected function match_hierarchy() {
        
        return '';
    }

    protected function match_format() {
        
        return '';
    }

    // public function get_jsonencode_options() {
        
    //     // If there are not results, it must produce {} in the .js file, not []
    //     // Documentation: https://secure.php.net/manual/en/function.json-encode.php
    //     return JSON_FORCE_OBJECT;
    // }
}
