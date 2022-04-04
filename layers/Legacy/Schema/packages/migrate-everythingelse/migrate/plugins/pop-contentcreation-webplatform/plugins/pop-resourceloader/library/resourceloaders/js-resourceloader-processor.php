<?php

class PoP_ContentCreation_ResourceLoaderProcessor extends PoP_JSResourceLoaderProcessor
{
    public final const RESOURCE_FEATUREDIMAGE = 'featuredimage';
    public final const RESOURCE_MEDIAMANAGERCORS = 'mediamanager-cors';
    public final const RESOURCE_MEDIAMANAGER = 'mediamanager';
    public final const RESOURCE_MEDIAMANAGERSTATE = 'mediamanager-state';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_FEATUREDIMAGE],
            [self::class, self::RESOURCE_MEDIAMANAGERCORS],
            [self::class, self::RESOURCE_MEDIAMANAGER],
            [self::class, self::RESOURCE_MEDIAMANAGERSTATE],
        ];
    }
    
    public function getFilename(array $resource)
    {
        $filenames = array(
            self::RESOURCE_FEATUREDIMAGE => 'featuredimage',
            self::RESOURCE_MEDIAMANAGERCORS => 'mediamanager-cors',
            self::RESOURCE_MEDIAMANAGER => 'mediamanager',
            self::RESOURCE_MEDIAMANAGERSTATE => 'mediamanager-state',
        );
        if ($filename = $filenames[$resource[1]]) {
            return $filename;
        }

        return parent::getFilename($resource);
    }
    
    public function getVersion(array $resource)
    {
        return POP_CONTENTCREATIONWEBPLATFORM_VERSION;
    }
    
    public function getDir(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        switch ($resource[1]) {
            case self::RESOURCE_MEDIAMANAGERCORS:
            case self::RESOURCE_MEDIAMANAGER:
            case self::RESOURCE_MEDIAMANAGERSTATE:
                return POP_CONTENTCREATIONWEBPLATFORM_DIR.'/js/'.$subpath.'libraries/mediamanager';
        }

        return POP_CONTENTCREATIONWEBPLATFORM_DIR.'/js/'.$subpath.'libraries';
    }
    
    public function getAssetPath(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_MEDIAMANAGERCORS:
            case self::RESOURCE_MEDIAMANAGER:
            case self::RESOURCE_MEDIAMANAGERSTATE:
                return POP_CONTENTCREATIONWEBPLATFORM_DIR.'/js/libraries/mediamanager/'.$this->getFilename($resource).'.js';
        }
    
        return POP_CONTENTCREATIONWEBPLATFORM_DIR.'/js/libraries/'.$this->getFilename($resource).'.js';
    }
    
    public function getPath(array $resource)
    {
        $afterpath = '';
        switch ($resource[1]) {
            case self::RESOURCE_MEDIAMANAGERCORS:
            case self::RESOURCE_MEDIAMANAGER:
            case self::RESOURCE_MEDIAMANAGERSTATE:
                $afterpath = '/mediamanager';
                break;
        }

        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POP_CONTENTCREATIONWEBPLATFORM_URL.'/js/'.$subpath.'libraries'.$afterpath;
    }

    public function getJsobjects(array $resource)
    {
        $objects = array(
            self::RESOURCE_FEATUREDIMAGE => array(
                'FeaturedImage',
            ),
            self::RESOURCE_MEDIAMANAGERCORS => array(
                'MediaManagerCORS',
            ),
            self::RESOURCE_MEDIAMANAGER => array(
                'MediaManager',
            ),
            self::RESOURCE_MEDIAMANAGERSTATE => array(
                'MediaManagerState',
            ),
        );
        if ($object = $objects[$resource[1]]) {
            return $object;
        }

        return parent::getJsobjects($resource);
    }
    
    public function getDecoratedResources(array $resource)
    {
        $decorated = parent::getDecoratedResources($resource);
    
        switch ($resource[1]) {
            case self::RESOURCE_MEDIAMANAGER:
            case self::RESOURCE_MEDIAMANAGERCORS:
                $decorated[] = [self::class, self::RESOURCE_FEATUREDIMAGE];
                $decorated[] = [PoP_TinyMCE_ResourceLoaderProcessor::class, PoP_TinyMCE_ResourceLoaderProcessor::RESOURCE_EDITOR];
                break;
        }

        switch ($resource[1]) {
            case self::RESOURCE_MEDIAMANAGERCORS:
                $decorated[] = [self::class, self::RESOURCE_MEDIAMANAGER];
                break;
        }

        return $decorated;
    }
}


