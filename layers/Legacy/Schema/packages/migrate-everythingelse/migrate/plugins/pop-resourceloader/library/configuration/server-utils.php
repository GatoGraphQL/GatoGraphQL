<?php

use PoP\ComponentModel\ComponentConfiguration as ComponentModelComponentConfiguration;

class PoP_ResourceLoader_ServerUtils
{
    public static function useCodeSplitting()
    {
        return getenv('USE_CODE_SPLITTING') !== false ? strtolower(getenv('USE_CODE_SPLITTING')) == "true" : false;
    }

    public static function generateCodeSplittingFiles()
    {
        // If doing code-splitting then we need to generate the files
        if (self::useCodeSplitting()) {
            return true;
        }

        return getenv('GENERATE_CODE_SPLITTING_FILES') !== false ? strtolower(getenv('GENERATE_CODE_SPLITTING_FILES')) == "true" : false;
    }

    public static function includeResourcesInHeader()
    {
        return self::getResourcesIncludeType() == 'header';
    }

    public static function includeResourcesInBody()
    {
        $bodies = array(
            'body',
            'body-inline',
        );
        return in_array(self::getResourcesIncludeType(), $bodies);
    }

    public static function getResourcesIncludeType()
    {
        // Include in the body is only valid when doing server-side rendering, code-splitting, and the enqueue type is 'resource'
        if ((!defined('POP_SSR_INITIALIZED') || PoP_SSR_ServerUtils::disableServerSideRendering()) || !self::useCodeSplitting() || self::getEnqueuefileType() != 'resource') {
            return 'header';
        }

        // Allow specific pages to set this value to false
        // Eg: when generating the Service Workers, we need to register all of the CSS files to output them in the precache list
        if ($include_type = \PoP\Root\App::getHookManager()->applyFilters('getResourcesIncludeType', '')) {
            return $include_type;
        }

        if (getenv('RESOURCES_INCLUDE_TYPE') !== false) {
            $includeType = getenv('RESOURCES_INCLUDE_TYPE');
            // Make sure the defined value is valid
            $values = array(
                'header',
                'body',
                'body-inline',
            );
            if (in_array($includeType, $values)) {
                return $includeType;
            }
        }

        // Default value
        return 'header';
    }

    public static function generateLoadingframeResourceMapping()
    {
        // This constant allows to create all configuration/cache files to operate the site, possibly in a STAGING environment before deploying it to LIVE
        // (such as when running /generate-theme/), so that the site in LIVE will already be fully calculated, and only DB data must be gathered and then merged with the cached configuration to produce the final HTML/JSON
        // If this is desired, then ALL produced files must be copied from STAGING to PROD!
        // In particular, all files required to be copied are:
        // 1- bundle(group)-resourceloader-mapping.json
        // 2- resourceloader-generatedfiles.json
        // 3- All pop-memory/ files
        // All files under pop-memory/ are way too many, and it is tempting to not include them in the release, but still include resourceloader-generatedfiles.json
        // These files must ALL be copied! We can't copy just `resourceloader-generatedfiles.json` but not the pop-memory/ files,
        // or the application will not work in function `isDefer()` for the resourceLoaders, which depend on pop-memory/ to find out if the resource must be set as "defer" or not
        // The problem is here: if accessing the cached content from file resourceloader-generatedfiles.json, then pop-memory/ files
        // will not get regenerated on runtime, when doing:
        // protected function registerScriptsOrStyles($type) {
        //     ...
        //     if ($type == POP_RESOURCELOADER_RESOURCETYPE_JS) {
        //         $resources = $pop_resourceloader_generatedfilesmanager->getJsResources($model_instance_id);
        //     }
        //     elseif ($type == POP_RESOURCELOADER_RESOURCETYPE_CSS) {
        //         $resources = $pop_resourceloader_generatedfilesmanager->getCssResources($model_instance_id);
        //     }
        //     ...
        // Then, we need consistency: we either use resourceloader-generatedfiles.json and copy the pop-memory/ files from STAGING to PROD,
        // or none and we generate these on runtime

        // Only valid when doing code-splitting
        if (!self::useCodeSplitting()) {
            return false;
        }

        // If generating either bundles or bundlegroups, then it can be done with no much processing needed, so just do it
        if (self::generateBundleFiles() || self::generateBundlegroupFiles()) {
            return true;
        }

        return getenv('GENERATE_LOADING_FRAME_RESOURCE_MAPPING') !== false ? strtolower(getenv('GENERATE_LOADING_FRAME_RESOURCE_MAPPING')) == "true" : false;
    }

    public static function generateBundlefilesOnRuntime()
    {
        // Only valid when doing code-splitting
        if (!self::useCodeSplitting()) {
            return false;
        }

        // If generating either bundles or bundlegroups, and the enqueue type is the corresponding one, then the bundle(group) files will be generated statically, so no need
        $enqueuefile_type = self::getEnqueuefileType();
        if ($enqueuefile_type == 'resource'
            || ($enqueuefile_type == 'bundle' && self::generateBundleFiles())
            || ($enqueuefile_type == 'bundlegroup' && self::generateBundlegroupFiles())
        ) {
            return false;
        }

        return getenv('GENERATE_BUNDLE_FILES_ON_RUNTIME') !== false ? strtolower(getenv('GENERATE_BUNDLE_FILES_ON_RUNTIME')) == "true" : false;
    }

    public static function generateBundleFiles()
    {
        // Only valid when doing code-splitting
        if (!self::useCodeSplitting()) {
            return false;
        }

        return getenv('GENERATE_BUNDLE_FILES') !== false ? strtolower(getenv('GENERATE_BUNDLE_FILES')) == "true" : false;
    }

    public static function generateBundlegroupFiles()
    {
        // Only valid when doing code-splitting
        if (!self::useCodeSplitting()) {
            return false;
        }

        return getenv('GENERATE_BUNDLEGROUP_FILES') !== false ? strtolower(getenv('GENERATE_BUNDLEGROUP_FILES')) == "true" : false;
    }

    public static function getBundlesChunkSize()
    {
        return getenv('BUNDLE_CHUNK_SIZE') !== false ? (int) getenv('BUNDLE_CHUNK_SIZE') : 4;
    }

    public static function bundleExternalFiles()
    {
        // If using CDN resources, then do not pack them inside
        return !PoP_WebPlatform_ServerUtils::accessExternalcdnResources();
    }

    public static function getEnqueuefileType($disable_hooks = false)
    {
        if (!$disable_hooks) {
            // There are requests that can only work with a specific type
            // Eg: the AppShell, it must always use 'resource', or otherwise it will need to load extra bundle(group) files,
            // making the initial SW pre-fetch heavy, and not allowing to easily create the AppShell for the different thememodes (embed, print)
            if ($enqueuefile_type = \PoP\Root\App::getHookManager()->applyFilters('getEnqueuefileType', '')) {
                return $enqueuefile_type;
            }
        }

        if (getenv('ENQUEUE_FILES_TYPE') !== false) {
            $fileType = getenv('ENQUEUE_FILES_TYPE');
            // Make sure the defined value is valid
            $values = array(
                'bundlegroup',
                'bundle',
                'resource',
            );
            if (in_array($fileType, $values)) {
                return $fileType;
            }
        }

        // Default value
        return 'resource';
    }

    public static function loadingBundlefile($disable_hooks = false)
    {
        $enqueuefile_type = self::getEnqueuefileType($disable_hooks);
        return $enqueuefile_type == 'bundlegroup' || $enqueuefile_type == 'bundle';
    }

    public static function loadframeResources()
    {
        return getenv('LOAD_FRAME_RESOURCES') !== false ? strtolower(getenv('LOAD_FRAME_RESOURCES')) == "true" : true;
    }
}
