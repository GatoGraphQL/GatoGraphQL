<?php

class PoP_ServiceWorkers_JSResourceLoaderProcessor extends PoP_JSResourceLoaderProcessor
{
    public final const RESOURCE_SW = 'sw';
    public final const RESOURCE_SWINITIAL = 'sw-initial';
    public final const RESOURCE_CORESW = 'core-sw';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_SW],
            [self::class, self::RESOURCE_SWINITIAL],
            [self::class, self::RESOURCE_CORESW],
        ];
    }
    
    public function getFilename(array $resource)
    {
        $filenames = array(
            self::RESOURCE_SW => 'sw',
            self::RESOURCE_SWINITIAL => 'sw-initial',
            self::RESOURCE_CORESW => 'core-sw',
        );
        if ($filename = $filenames[$resource[1]]) {
            return $filename;
        }

        return parent::getFilename($resource);
    }
    
    public function getVersion(array $resource)
    {
        return POP_SERVICEWORKERS_VERSION;
    }
    
    public function getDir(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POP_SERVICEWORKERS_DIR.'/js/'.$subpath.'libraries';
    }
    
    public function getAssetPath(array $resource)
    {
        return POP_SERVICEWORKERS_DIR.'/js/libraries/'.$this->getFilename($resource).'.js';
    }
        
    public function extractMapping(array $resource)
    {

        // No need to extract the mapping from this file (also, it doesn't exist under that getDir() folder)
        switch ($resource[1]) {
            case self::RESOURCE_SWINITIAL:
                return false;
        }
    
        return parent::extractMapping($resource);
    }
    
    public function getPath(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POP_SERVICEWORKERS_URL.'/js/'.$subpath.'libraries';
    }

    public function getJsobjects(array $resource)
    {
        $objects = array(
            self::RESOURCE_SW => array(
                'ServiceWorkers',
            ),
            self::RESOURCE_CORESW => array(
                'CoreSW',
            ),
        );
        if ($object = $objects[$resource[1]]) {
            return $object;
        }

        return parent::getJsobjects($resource);
    }
    
    public function inline(array $resource)
    {
        switch ($resource[1]) {
         // File sw-initial.js is needed because executing `fetch(cacheBustRequest, fetchOpts)` in service-worker.js happens so fast,
         // that quite likely sw.js is still not loaded, so it will not catch that first message triggered when the initial page has been updated
            case self::RESOURCE_SWINITIAL:
                return true;
        }
    
        return parent::inline($resource);
    }
    
    public function inFooter(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_SWINITIAL:
                return false;
        }
    
        return parent::inFooter($resource);
    }
    
    public function canBundle(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_SWINITIAL:
                return false;
        }
    
        return parent::canBundle($resource);
    }
    
    public function getDecoratedResources(array $resource)
    {
        $decorated = parent::getDecoratedResources($resource);
    
        switch ($resource[1]) {
            case self::RESOURCE_SWINITIAL:
            case self::RESOURCE_CORESW:
                $decorated[] = [self::class, self::RESOURCE_SW];
                break;
        }

        return $decorated;
    }
}


