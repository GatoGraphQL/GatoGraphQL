<?php

class EM_PoPProcessors_ResourceLoaderProcessor extends PoP_JSResourceLoaderProcessor
{
    public final const RESOURCE_MAPCOLLECTION = 'em-map-collection';
    public final const RESOURCE_TYPEAHEADMAPSELECTABLE = 'em-typeahead-map-selectable';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_MAPCOLLECTION],
            [self::class, self::RESOURCE_TYPEAHEADMAPSELECTABLE],
        ];
    }
    
    public function getFilename(array $resource)
    {
        $filenames = array(
            self::RESOURCE_MAPCOLLECTION => 'map-collection',
            self::RESOURCE_TYPEAHEADMAPSELECTABLE => 'typeahead-map-selectable',
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
        return POP_LOCATIONSWEBPLATFORM_DIR.'/js/'.$subpath.'libraries';
    }
    
    public function getAssetPath(array $resource)
    {
        return POP_LOCATIONSWEBPLATFORM_DIR.'/js/libraries/'.$this->getFilename($resource).'.js';
    }
    
    public function getPath(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POP_LOCATIONSWEBPLATFORM_URL.'/js/'.$subpath.'libraries';
    }

    public function getJsobjects(array $resource)
    {
        $objects = array(
            self::RESOURCE_MAPCOLLECTION => array(
                'MapCollection',
            ),
            self::RESOURCE_TYPEAHEADMAPSELECTABLE => array(
                'TypeaheadMapSelectable',
            ),
        );
        if ($object = $objects[$resource[1]]) {
            return $object;
        }

        return parent::getJsobjects($resource);
    }
}


