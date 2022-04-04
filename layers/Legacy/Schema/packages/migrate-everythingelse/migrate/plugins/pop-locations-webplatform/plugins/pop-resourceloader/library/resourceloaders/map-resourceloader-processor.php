<?php

// The 'map.js' file has been divided into the following subunits, to allow as much code as possible to be loaded as defer
// Eg: executing function 'map' is non-critical, so the current 'map.js' is loaded defer, however the rest is loaded immediately
// from calling pop.MapInitMarker.initMarker in some .tmpl files and the consequent dependencies
class EM_PoPProcessors_MapResourceLoaderProcessor extends PoP_JSResourceLoaderProcessor
{
    public final const RESOURCE_MAP = 'em-mapa';
    public final const RESOURCE_MAPMEMORY = 'em-map-memory';
    public final const RESOURCE_MAPINITMARKER = 'em-map-initmarker';
    public final const RESOURCE_MAPRUNTIME = 'em-map-runtime';
    public final const RESOURCE_MAPRUNTIMEMEMORY = 'em-map-runtime-memory';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_MAP],
            [self::class, self::RESOURCE_MAPMEMORY],
            [self::class, self::RESOURCE_MAPINITMARKER],
            [self::class, self::RESOURCE_MAPRUNTIME],
            [self::class, self::RESOURCE_MAPRUNTIMEMEMORY],
        ];
    }
    
    public function getFilename(array $resource)
    {
        $filenames = array(
            self::RESOURCE_MAP => 'map',
            self::RESOURCE_MAPMEMORY => 'map-memory',
            self::RESOURCE_MAPINITMARKER => 'map-initmarker',
            self::RESOURCE_MAPRUNTIME => 'map-runtime',
            self::RESOURCE_MAPRUNTIMEMEMORY => 'map-runtime-memory',
        );
        if ($filename = $filenames[$resource[1]]) {
            return $filename;
        }

        return parent::getFilename($resource);
    }
    
    public function getVersion(array $resource)
    {
        return POP_LOCATIONSWEBPLATFORM_VERSION;
    }
    
    public function getDir(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POP_LOCATIONSWEBPLATFORM_DIR.'/js/'.$subpath.'libraries/map';
    }
    
    public function getAssetPath(array $resource)
    {
        return POP_LOCATIONSWEBPLATFORM_DIR.'/js/libraries/map/'.$this->getFilename($resource).'.js';
    }
    
    public function getPath(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POP_LOCATIONSWEBPLATFORM_URL.'/js/'.$subpath.'libraries/map';
    }

    public function getJsobjects(array $resource)
    {
        $objects = array(
            self::RESOURCE_MAP => array(
                'Map',
            ),
            self::RESOURCE_MAPMEMORY => array(
                'MapMemory',
            ),
            self::RESOURCE_MAPINITMARKER => array(
                'MapInitMarker',
            ),
            self::RESOURCE_MAPRUNTIME => array(
                'MapRuntime',
            ),
            self::RESOURCE_MAPRUNTIMEMEMORY => array(
                'MapRuntimeMemory',
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
            case self::RESOURCE_MAP:
                $dependencies[] = [PoP_CoreProcessors_VendorJSResourceLoaderProcessor::class, PoP_CoreProcessors_VendorJSResourceLoaderProcessor::RESOURCE_EXTERNAL_GMAPS];
                break;
        }

        return $dependencies;
    }
}


