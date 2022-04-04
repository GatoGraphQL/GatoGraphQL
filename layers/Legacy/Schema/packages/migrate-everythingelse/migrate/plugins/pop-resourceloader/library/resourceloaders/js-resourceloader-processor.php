<?php

class PoP_ResourceLoader_JSResourceLoaderProcessor extends PoP_JSResourceLoaderProcessor
{
    public final const RESOURCE_HANDLEBARSHELPERSRESOURCELOADERHOOKS = 'handlebarshelpers-resourceloader-hooks';

    public function getResourcesToProcess()
    {
        $ret = array();

        // Loading this script can be disabled by SPAResourceLoader
        if (PoP_ResourceLoader_Utils::registerHandlebarshelperScript()) {
            $ret[] = [self::class, self::RESOURCE_HANDLEBARSHELPERSRESOURCELOADERHOOKS];
        }

        return $ret;
    }
    
    public function getFilename(array $resource)
    {
        $filenames = array(
            self::RESOURCE_HANDLEBARSHELPERSRESOURCELOADERHOOKS => 'handlebarshelpers-resourceloader-hooks',
        );
        if ($filename = $filenames[$resource[1]]) {
            return $filename;
        }

        return parent::getFilename($resource);
    }
    
    public function getVersion(array $resource)
    {
        return POP_RESOURCELOADER_VERSION;
    }
    
    public function getDir(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POP_RESOURCELOADER_DIR.'/js/'.$subpath.'libraries';
    }
    
    public function getAssetPath(array $resource)
    {
        return POP_RESOURCELOADER_DIR.'/js/libraries/'.$this->getFilename($resource).'.js';
    }
    
    public function getPath(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POP_RESOURCELOADER_URL.'/js/'.$subpath.'libraries';
    }

    public function getJsobjects(array $resource)
    {
        $objects = array(
            self::RESOURCE_HANDLEBARSHELPERSRESOURCELOADERHOOKS => array(
                'ResourceLoaderHandlebarsHelperHooks',
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
            case self::RESOURCE_HANDLEBARSHELPERSRESOURCELOADERHOOKS:
                $decorated[] = [PoP_FrontEnd_JSResourceLoaderProcessor::class, PoP_FrontEnd_JSResourceLoaderProcessor::RESOURCE_JSLIBRARYMANAGER];
                break;
        }

        return $decorated;
    }
}


