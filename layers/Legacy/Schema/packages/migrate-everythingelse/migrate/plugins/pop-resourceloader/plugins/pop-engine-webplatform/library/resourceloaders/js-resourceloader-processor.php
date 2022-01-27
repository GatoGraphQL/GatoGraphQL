<?php

class PoP_FrontEnd_JSResourceLoaderProcessor extends PoP_JSResourceLoaderProcessor
{
    public const RESOURCE_COMPATIBILITY = 'compatibility';
    public const RESOURCE_HISTORY = 'history';
    public const RESOURCE_INTERCEPTORS = 'interceptors';
    public const RESOURCE_JSLIBRARYMANAGER = 'jslibrary-manager';
    public const RESOURCE_JSRUNTIMEMANAGER = 'jsruntime-manager';
    public const RESOURCE_PAGESECTIONMANAGER = 'pagesection-manager';
    public const RESOURCE_POPMANAGER = 'pop-manager';
    public const RESOURCE_DATASTORE = 'data-store';
    public const RESOURCE_POPUTILS = 'pop-utils';
    public const RESOURCE_UTILS = 'utils';
    public const RESOURCE_LIFECYCLE = 'lifecycle';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_COMPATIBILITY],
            [self::class, self::RESOURCE_HISTORY],
            [self::class, self::RESOURCE_INTERCEPTORS],
            [self::class, self::RESOURCE_JSLIBRARYMANAGER],
            [self::class, self::RESOURCE_JSRUNTIMEMANAGER],
            [self::class, self::RESOURCE_PAGESECTIONMANAGER],
            [self::class, self::RESOURCE_POPMANAGER],
            [self::class, self::RESOURCE_DATASTORE],
            [self::class, self::RESOURCE_POPUTILS],
            [self::class, self::RESOURCE_UTILS],
            [self::class, self::RESOURCE_LIFECYCLE],
        ];
    }
    
    public function getFilename(array $resource)
    {
        $filenames = array(
            self::RESOURCE_COMPATIBILITY => 'compatibility',
            self::RESOURCE_HISTORY => 'history',
            self::RESOURCE_INTERCEPTORS => 'interceptors',
            self::RESOURCE_JSLIBRARYMANAGER => 'jslibrary-manager',
            self::RESOURCE_JSRUNTIMEMANAGER => 'jsruntime-manager',
            self::RESOURCE_PAGESECTIONMANAGER => 'pagesection-manager',
            self::RESOURCE_POPMANAGER => 'pop-manager',
            self::RESOURCE_DATASTORE => 'data-store',
            self::RESOURCE_POPUTILS => 'pop-utils',
            self::RESOURCE_UTILS => 'utils',
            self::RESOURCE_LIFECYCLE => 'lifecycle',
        );
        if ($filename = $filenames[$resource[1]]) {
            return $filename;
        }

        return parent::getFilename($resource);
    }
    
    public function getVersion(array $resource)
    {
        return POP_ENGINEWEBPLATFORM_VERSION;
    }
    
    public function getDir(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POP_ENGINEWEBPLATFORM_DIR.'/js/'.$subpath.'libraries';
    }
    
    public function getAssetPath(array $resource)
    {
        return POP_ENGINEWEBPLATFORM_DIR.'/js/libraries/'.$this->getFilename($resource).'.js';
    }
        
    public function extractMapping(array $resource)
    {

        // No need to extract the mapping from this file (also, it doesn't exist under that getDir() folder)
        switch ($resource[1]) {
            case self::RESOURCE_COMPATIBILITY:
            case self::RESOURCE_UTILS:
                return false;
        }
    
        return parent::extractMapping($resource);
    }
    
    public function getPath(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POP_ENGINEWEBPLATFORM_URL.'/js/'.$subpath.'libraries';
    }

    public function getJsobjects(array $resource)
    {
        $objects = array(
            self::RESOURCE_HISTORY => array(
                'BrowserHistory',
            ),
            self::RESOURCE_INTERCEPTORS => array(
                'URLInterceptors',
            ),
            self::RESOURCE_JSLIBRARYMANAGER => array(
                'JSLibraryManager',
            ),
            self::RESOURCE_JSRUNTIMEMANAGER => array(
                'JSRuntimeManager',
            ),
            self::RESOURCE_PAGESECTIONMANAGER => array(
                'PageSectionManager',
            ),
            self::RESOURCE_POPMANAGER => array(
                'Manager',
            ),
            self::RESOURCE_DATASTORE => array(
                'DataStore',
            ),
            self::RESOURCE_POPUTILS => array(
                'Utils',
            ),
            self::RESOURCE_LIFECYCLE => array(
                'Lifecycle',
            ),
        );

        if ($object = $objects[$resource[1]]) {
            return $object;
        }

        return parent::getJsobjects($resource);
    }
    
    public function getDependencies(array $resource)
    {
        $dependencies = parent::getDependencies($resource);
    
        switch ($resource[1]) {
            case self::RESOURCE_POPMANAGER:
                // PoP Manager is a special case: it's the one library that, we are sure, will always be executed
                // So, inject the dependencies to this resource, to make sure they will always be loaded
                // All templates depend on the handlebars runtime. Allow plugins to add their own dependencies
                $manager_dependencies = array(
                    [self::class, self::RESOURCE_COMPATIBILITY],
                    [self::class, self::RESOURCE_UTILS],
                    [self::class, self::RESOURCE_POPUTILS],
                    [self::class, self::RESOURCE_JSLIBRARYMANAGER],
                    [self::class, self::RESOURCE_DATASTORE],

                    // The resources below are not strictly needed to be added as dependencies, since they are mapped inside popManager.init internal/external method calls
                    // However, if the mapping has not been generated, then that dependency will fail.
                    // Just to be sure, add them as dependencies too
                    [self::class, self::RESOURCE_HISTORY],
                    [self::class, self::RESOURCE_INTERCEPTORS],
                    [self::class, self::RESOURCE_JSRUNTIMEMANAGER],
                    [self::class, self::RESOURCE_PAGESECTIONMANAGER],
                );

                if ($manager_dependencies = \PoP\Root\App::applyFilters(
                    'PoP_FrontEnd_ResourceLoaderProcessor:dependencies:manager',
                    $manager_dependencies
                )
                ) {
                    $dependencies = array_merge(
                        $dependencies,
                        $manager_dependencies
                    );
                }
                break;
        }

        return $dependencies;
    }
}


