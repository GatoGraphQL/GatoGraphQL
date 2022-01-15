<?php

use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoPSchema\SchemaCommons\Facades\CMS\CMSServiceFacade;
use PoP\SPA\ModuleFilters\Page;

abstract class PoP_SPAResourceLoader_FileReproduction_ResourcesConfigBase extends \PoP\FileStore\File\AbstractRenderableFileFragment
{
    protected function getOptions()
    {
        $options = array(
            'match-paths' => $this->matchPaths(),
            'match-nature' => $this->matchNature(),
            'match-format' => $this->matchFormat(),
        );

        // Calculate the resources for the current request already, to make sure we don't call the function with the application state modified
        if (!PoP_ResourceLoader_ServerUtils::loadframeResources()) {
            // To obtain the list of all resources that are always loaded, we can simply calculate the resources for this actual request,
            // for page /generate-theme/ (POP_SYSTEM_ROUTE_SYSTEM_GENERATETHEME), which because it doesn't add blocks or anything to the output,
            // it is strip of extra stuff, making it the minimum loading experience
            $options['ignore-resources'] = PoPWebPlatform_SPAResourceLoader_ScriptsAndStylesUtils::getLoadingframeResources();
        }

        return $options;
    }

    /**
     * @return mixed[]
     */
    public function getConfiguration(): array
    {
        $configuration = parent::getConfiguration();
        $cmsService = CMSServiceFacade::getInstance();

        // Domain
        $configuration['$domain'] = $cmsService->getSiteURL();

        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var Page */
        $page = $instanceManager->getInstance(Page::class);

        // Get all the resources, for the different natures
        // Notice that we get the callback to filter results from the instantiating class
        $options = $this->getOptions();
        $resource_mapping = PoP_ResourceLoader_FileReproduction_Utils::getResourceMapping(
            $page->getName(),
            $options
        );

        // Print the resource mapping information into the config file's configuration
        $configuration['$matchPaths'] = $this->matchPaths();
        $configuration['$matchNature'] = $this->matchNature();
        $configuration['$matchFormat'] = $this->matchFormat();

        $configuration['$keys'] = $resource_mapping['keys'];
        $configuration['$sources'] = $resource_mapping['sources'];
        $configuration['$types'] = $resource_mapping['types'];
        $configuration['$orderedLoadResources'] = $resource_mapping['ordered-load-resources'];

        // We can't do array_merge for bundles and bundlegroups, or their $bundleId/$bundleGroupId, which is the key of each
        // array, will be lost. Then, use the "+" operator, which preserves keys
        // Important: The keys in 'js' and 'css' will NEVER overlap! Because when generating the $bundleId/$bundleGroupId, we call
        // the same process getBundleId($resources)/getBundlegroupId($resources), being $resources different
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
                foreach ($resource_mapping['resources'][$type][$subtype]['flat'] as $nature => $key_bundlegroups) {
                    foreach ($key_bundlegroups as $keyId => $bundleGroupIds) {
                        $merged_resources[$nature][$keyId] = $merged_resources[$nature][$keyId] ?? array();
                        $merged_resources[$nature][$keyId] = array_merge(
                            $merged_resources[$nature][$keyId],
                            $bundleGroupIds
                        );
                    }
                }
                foreach ($resource_mapping['resources'][$type][$subtype]['path'] as $nature => $path_key_bundlegroup) {
                    foreach ($path_key_bundlegroup as $path => $key_bundlegroups) {
                        foreach ($key_bundlegroups as $keyId => $bundleGroupIds) {
                            $merged_resources[$nature][$path][$keyId] = $merged_resources[$nature][$path][$keyId] ?? array();
                            $merged_resources[$nature][$path][$keyId] = array_merge(
                                $merged_resources[$nature][$path][$keyId],
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

    protected function matchPaths()
    {
        return array();
    }

    protected function matchNature()
    {
        return '';
    }

    protected function matchFormat()
    {
        return '';
    }

    // public function getJsonEncodeOptions(): int {

    //     // If there are not results, it must produce {} in the .js file, not []
    //     // Documentation: https://secure.php.net/manual/en/function.json-encode.php
    //     return JSON_FORCE_OBJECT;
    // }
}
