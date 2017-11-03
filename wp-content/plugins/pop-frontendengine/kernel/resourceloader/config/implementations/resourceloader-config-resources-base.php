<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ResourceLoader_FileReproduction_ResourcesConfigBase extends PoP_Engine_FileReproductionBase {

    public function get_configuration() {
        
        $configuration = parent::get_configuration();

        // Domain
        $configuration['$domain'] = get_site_url();

        // Get all the resources, for the different hierarchies
        // Notice that we get the callback to filter results from the instantiating class
        $options = array(
            'match-paths' => $this->match_paths(),
            'match-hierarchy' => $this->match_hierarchy(),
            'match-format' => $this->match_format(),
        );
        $resource_mapping = PoP_ResourceLoader_FileReproduction_Utils::get_resource_mapping($options);

        // Print the resource mapping information into the config file's configuration
        $configuration['$bundles'] = $resource_mapping['bundles'];
        $configuration['$bundleGroups'] = $resource_mapping['bundle-groups'];
        $configuration['$keys'] = $resource_mapping['keys'];
        $configuration['$resources'] = array(
            'js' => array_merge(
                $resource_mapping['resources']['js']['flat'],
                $resource_mapping['resources']['js']['path']
            ),
        );
        $configuration['$sources'] = $resource_mapping['sources'];
        $configuration['$orderedLoadResources'] = $resource_mapping['ordered-load-resources'];
        
        $configuration['$matchPaths'] = $this->match_paths();
        $configuration['$matchHierarchy'] = $this->match_hierarchy();
        $configuration['$matchFormat'] = $this->match_format();

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
