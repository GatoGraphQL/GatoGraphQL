<?php
use PoP\ComponentModel\Facades\Info\ApplicationInfoFacade;
use PoP\ComponentModel\State\ApplicationState;
use PoP\FileStore\Facades\FileStoreFacade;
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_ResourceLoader_FileGenerator_BundleFilesBase {

    protected $resource_mapping, $generated;

    public function __construct() {

        $this->generated = array(
            'bundlegroup' => array(),
            'bundle' => array(),
        );
    }

    protected function getResourceMapping() {

        return array();
    }

    protected function generateBundleFiles() {

        $enqueuefile_type = PoP_ResourceLoader_ServerUtils::getEnqueuefileType();
        return $enqueuefile_type == 'bundle';
    }

    protected function generateBundlegroupFiles() {

        $enqueuefile_type = PoP_ResourceLoader_ServerUtils::getEnqueuefileType();
        return $enqueuefile_type == 'bundlegroup';
    }

    public function generate($options = array()) {

        if ($this->resource_mapping = $this->getResourceMapping()) {

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
                    foreach ($this->resource_mapping['resources'][$type][$subtype]['flat'] as $nature => $key_bundlegroups) {
                        foreach ($key_bundlegroups as $keyId => $bundleGroupIds) {

                            // When generating the bundle(group)s, the key is the cache name
                            $model_instance_id = array_search($keyId, $this->resource_mapping['keys']);
                            foreach ($bundleGroupIds as $bundleGroupId) {

                                // Generate the bundlegroup file with all the resources inside
                                $this->generateItems($model_instance_id, $type, $subtype, $bundleGroupId, $options);
                            }
                        }
                    }
                    foreach ($this->resource_mapping['resources'][$type][$subtype]['path'] as $nature => $path_key_bundlegroup) {
                        foreach ($path_key_bundlegroup as $path => $key_bundlegroups) {
                            foreach ($key_bundlegroups as $keyId => $bundleGroupIds) {
                                $model_instance_id = array_search($keyId, $this->resource_mapping['keys']);
                                foreach ($bundleGroupIds as $bundleGroupId) {
                                    $this->generateItems($model_instance_id, $type, $subtype, $bundleGroupId, $options);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    protected function generateItems($model_instance_id, $type, $subtype, $bundleGroupId, $options = array()) {

        // Intersect with the "only-include" type and ids, if needed
        $only_include_type = '';
        $only_include_ids = array();
        if ($options['only-include'] ?? null) {
            $only_include_type = $options['enqueuefile-type'] ?? null;
            $only_include_ids = $options['ids'] ?? null;
        }

        if (!$only_include_type || $only_include_ids == 'bundle') {
            // Generate the bundlegroup file with all the resources inside
            $bundle_ids = $this->resource_mapping['bundle-groups'][$type][$subtype][$bundleGroupId];
            $bundles = $this->resource_mapping['bundles'][$type][$subtype];
            $bundlegroup_resources = array();
            foreach ($bundle_ids as $bundleId) {

                $bundlegroup_resources = array_merge(
                    $bundlegroup_resources,
                    $bundles[$bundleId]
                );
            }

            if ($only_include_type == 'bundle') {

                $bundle_ids = array_values(array_intersect(
                    $bundle_ids,
                    $only_include_ids
                ));
            }
        }

        // Generate bundleGroup containing all resources
        if (!$only_include_type || ($only_include_type == 'bundlegroup' && in_array($bundleGroupId, $only_include_ids))) {

            if ($this->generateBundlegroupFiles()) {

                $this->generateItem('bundlegroup', $bundleGroupId, $type, $subtype, $bundlegroup_resources, $options);
            }
        }

        if (!$only_include_type || ($only_include_type == 'bundle' && $bundle_ids)) {

            // Generate bundles containing all resources
            if ($this->generateBundleFiles()) {

                foreach ($bundle_ids as $bundleId) {

                    $this->generateItem('bundle', $bundleId, $type, $subtype, $bundles[$bundleId], $options);
                }
            }
        }

        // Comment Leo 21/01/2018: this logic to generate "normal"/"defer"/"async" has all been moved here (originally at wp-content/plugins/pop-engine-webplatform/kernel/resourceloader/config/js-bundlefile-filegenerator-base.php)
        // because we need to create a bundleGroupId for the deferred list of resources, instead of attaching "-defer" to the filename:
        // this last one doesn't work, since different pages, with different $model_instance_id, could have same $resources but different $normal_resources/$defer_resources,
        // depending on the JS functions being differently set as critical/non-critical
        // Then, just generate the $resources being passed
        if ($type == POP_RESOURCELOADER_RESOURCETYPE_JS) {

            if (!$only_include_type || ($only_include_type == 'bundle' && !empty($bundle_ids)) || ($only_include_type == 'bundlegroup' && in_array($bundleGroupId, $only_include_ids))) {

                global $pop_resourceloader_generatedfilesmanager;

                // Generate the following files:
                // 1. Immediate, without defer or async scripts
                // 2. Defer
                // 3. Async
                $resources_by_loading_type = PoPWebPlatform_ResourceLoader_ScriptsAndStylesUtils::splitJsResourcesByLoadingType($bundlegroup_resources, $model_instance_id);

                // resources
                $immediate_resources = $resources_by_loading_type['immediate']['resources'];
                $async_resources = $resources_by_loading_type['async']['resources'];
                $defer_resources = $resources_by_loading_type['defer']['resources'];
                $pop_resourceloader_generatedfilesmanager->setJsResourcesByLoadingType($model_instance_id, $subtype, POP_RESOURCELOADER_LOADINGTYPE_IMMEDIATE, $immediate_resources);
                $pop_resourceloader_generatedfilesmanager->setJsResourcesByLoadingType($model_instance_id, $subtype, POP_RESOURCELOADER_LOADINGTYPE_ASYNC, $async_resources);
                $pop_resourceloader_generatedfilesmanager->setJsResourcesByLoadingType($model_instance_id, $subtype, POP_RESOURCELOADER_LOADINGTYPE_DEFER, $defer_resources);

                if (!$only_include_type || ($only_include_type == 'bundlegroup' && in_array($bundleGroupId, $only_include_ids))) {

                    // bundleGroups
                    $immediate_bundleGroupId = $resources_by_loading_type['immediate']['bundlegroup'];
                    $async_bundleGroupId = $resources_by_loading_type['async']['bundlegroup'];
                    $defer_bundleGroupId = $resources_by_loading_type['defer']['bundlegroup'];
                    $pop_resourceloader_generatedfilesmanager->setJsBundlegroupIdByLoadingType($model_instance_id, $subtype, POP_RESOURCELOADER_LOADINGTYPE_IMMEDIATE, $immediate_bundleGroupId);
                    $pop_resourceloader_generatedfilesmanager->setJsBundlegroupIdByLoadingType($model_instance_id, $subtype, POP_RESOURCELOADER_LOADINGTYPE_ASYNC, $async_bundleGroupId);
                    $pop_resourceloader_generatedfilesmanager->setJsBundlegroupIdByLoadingType($model_instance_id, $subtype, POP_RESOURCELOADER_LOADINGTYPE_DEFER, $defer_bundleGroupId);
                    if ($this->generateBundlegroupFiles()) {

                        $this->generateItem('bundlegroup', $immediate_bundleGroupId, $type, $subtype, $immediate_resources, $options);
                        $this->generateItem('bundlegroup', $async_bundleGroupId, $type, $subtype, $async_resources, $options);
                        $this->generateItem('bundlegroup', $defer_bundleGroupId, $type, $subtype, $defer_resources, $options);
                    }
                }

                if (!$only_include_type || ($only_include_type == 'bundle' && !empty($bundle_ids))) {

                    // bundles
                    $immediate_bundleids = $resources_by_loading_type['immediate']['bundle-ids'];
                    $async_bundleids = $resources_by_loading_type['async']['bundle-ids'];
                    $defer_bundleids = $resources_by_loading_type['defer']['bundle-ids'];
                    $pop_resourceloader_generatedfilesmanager->setJsBundleIdsByLoadingType($model_instance_id, $subtype, POP_RESOURCELOADER_LOADINGTYPE_IMMEDIATE, $immediate_bundleids);
                    $pop_resourceloader_generatedfilesmanager->setJsBundleIdsByLoadingType($model_instance_id, $subtype, POP_RESOURCELOADER_LOADINGTYPE_ASYNC, $async_bundleids);
                    $pop_resourceloader_generatedfilesmanager->setJsBundleIdsByLoadingType($model_instance_id, $subtype, POP_RESOURCELOADER_LOADINGTYPE_DEFER, $defer_bundleids);
                    if ($this->generateBundleFiles()) {

                        $immediate_resourcebundles = $resources_by_loading_type['immediate']['bundles'];
                        $async_resourcebundles = $resources_by_loading_type['async']['bundles'];
                        $defer_resourcebundles = $resources_by_loading_type['defer']['bundles'];

                        for ($i=0; $i<count($immediate_bundleids); $i++) {

                            $immediate_bundleId = $immediate_bundleids[$i];
                            $immediate_bundle_resources = $immediate_resourcebundles[$i];
                            $this->generateItem('bundle', $immediate_bundleId, $type, $subtype, $immediate_bundle_resources, $options);
                        }
                        for ($i=0; $i<count($async_bundleids); $i++) {

                            $async_bundleId = $async_bundleids[$i];
                            $async_bundle_resources = $async_resourcebundles[$i];
                            $this->generateItem('bundle', $async_bundleId, $type, $subtype, $async_bundle_resources, $options);
                        }
                        for ($i=0; $i<count($defer_bundleids); $i++) {

                            $defer_bundleId = $defer_bundleids[$i];
                            $defer_bundle_resources = $defer_resourcebundles[$i];
                            $this->generateItem('bundle', $defer_bundleId, $type, $subtype, $defer_bundle_resources, $options);
                        }
                    }
                }
            }
        }
    }

    protected function generateItem($enqueuefile_type, $itemId, $type, $subtype, $resources, $options) {

        // Check that the bundleGroup is not null (eg: "async")
        // Check if this item has already been generated. If so, don't do it again
        if (!$itemId || in_array($itemId, $this->generated[$enqueuefile_type])) {
            return;
        }
        $this->generated[$enqueuefile_type][] = $itemId;

        // Generate the bundlegroup file with all the resources inside
        $file = PoP_ResourceLoader_FileGenerator_BundleFiles_Utils::getFile($enqueuefile_type, $type, $subtype);
        $file->setFilename($itemId.'.'.$type);
        $file->setResources($resources);
        // $file->generate();
        $renderer = new PoP_ResourceLoader_BundleFile_FileRenderer(FileStoreFacade::getInstance());
        $renderer->renderAndSave($file);

        // If we are generating the bundle(group) files on runtime, then trigger a hook to have these uploaded to S3
        if ($options['generate-item-triggerhook'] ?? null) {
            \PoP\Root\App::getHookManager()->doAction(
                'PoP_ResourceLoader_FileGenerator_BundleFilesBase:generate-item',
                $file->getFilepath(),
                $file->getGeneratedReferencedFiles(),
                $type,
                $subtype
            );
        }

        // Generate the version of the bundlefile, from the combined versions of all its resources
        $this->generateVersion($enqueuefile_type, $itemId, $resources);
    }

    protected function generateVersion($enqueuefile_type, $itemId, $resources) {

        global $pop_resourceloaderprocessor_manager;

        // Generate the version of the bundlefile, from the combined versions of all its resources
        $versions = array();
        foreach ($resources as $resource) {

            $versions[] = (string) $pop_resourceloaderprocessor_manager->getProcessor($resource)->getVersion($resource);
        }

        // Remove duplicates and order them
        $versions = array_unique($versions);
        array_multisort($versions);

        if (count($versions) > 1) {

            // If there is more than 1 version, obtain the hash from the combination of all of them, shortening it to only 8 digits
            $version = substr(hash('md5', implode('', $versions)), 0, 8);
        }
        elseif (count($versions) == 1) {

            // If there is only one version then use it directly
            $version = $versions[0];
        }
        else {

            // If it is an empty file, use the website version. This makes sure that an entry for this file is created in files bundle(group)-versions.json,
            // so that an empty file is not re-generated each time when using generate_bundlefile_on_runtime()
            $version = ApplicationInfoFacade::getInstance()->getVersion();
        }

        // Save it in hard disk for later use
        if ($enqueuefile_type == 'bundle') {

            PoP_ResourceLoaderProcessorUtils::setBundleVersion($itemId, $version);
        }
        elseif ($enqueuefile_type == 'bundlegroup') {

            PoP_ResourceLoaderProcessorUtils::setBundlegroupVersion($itemId, $version);
        }
    }
}
