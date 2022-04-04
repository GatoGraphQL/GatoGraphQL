<?php

class PoP_Locations_Bootstrap_ResourceLoaderProcessor extends PoP_JSResourceLoaderProcessor
{
    public final const RESOURCE_BOOTSTRAPMAPCOLLECTION = 'em-bootstrap-map-collection';
    public final const RESOURCE_BOOTSTRAPMAP = 'em-bootstrap-map';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_BOOTSTRAPMAPCOLLECTION],
            [self::class, self::RESOURCE_BOOTSTRAPMAP],
        ];
    }
    
    public function getFilename(array $resource)
    {
        $filenames = array(
            self::RESOURCE_BOOTSTRAPMAPCOLLECTION => 'bootstrap-map-collection',
            self::RESOURCE_BOOTSTRAPMAP => 'bootstrap-map',
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
        return POP_LOCATIONSWEBPLATFORM_DIR.'/js/'.$subpath.'libraries/bootstrap';
    }
    
    public function getAssetPath(array $resource)
    {
        return POP_LOCATIONSWEBPLATFORM_DIR.'/js/libraries/bootstrap/'.$this->getFilename($resource).'.js';
    }
    
    public function getPath(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POP_LOCATIONSWEBPLATFORM_URL.'/js/'.$subpath.'libraries/bootstrap';
    }

    public function getJsobjects(array $resource)
    {
        $objects = array(
            self::RESOURCE_BOOTSTRAPMAPCOLLECTION => array(
                'BootstrapMapCollection',
            ),
            self::RESOURCE_BOOTSTRAPMAP => array(
                'BootstrapMap',
            ),
        );
        if ($object = $objects[$resource[1]]) {
            return $object;
        }

        return parent::getJsobjects($resource);
    }
}


