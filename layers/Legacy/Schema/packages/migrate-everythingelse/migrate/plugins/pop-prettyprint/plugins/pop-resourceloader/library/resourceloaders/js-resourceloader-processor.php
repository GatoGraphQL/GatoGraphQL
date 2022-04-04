<?php

class PoP_PrettyPrint_ResourceLoaderProcessor extends PoP_JSResourceLoaderProcessor
{
    public final const RESOURCE_PRETTYPRINT = 'prettyprint';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_PRETTYPRINT],
        ];
    }
    
    public function getFilename(array $resource)
    {
        $filenames = array(
            self::RESOURCE_PRETTYPRINT => 'pop-prettyprint',
        );
        if ($filename = $filenames[$resource[1]]) {
            return $filename;
        }

        return parent::getFilename($resource);
    }
    
    public function getVersion(array $resource)
    {
        return POP_PRETTYPRINT_VERSION;
    }
    
    public function getDir(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POP_PRETTYPRINT_DIR.'/js/'.$subpath.'libraries';
    }
    
    public function getAssetPath(array $resource)
    {
        return POP_PRETTYPRINT_DIR.'/js/libraries/'.$this->getFilename($resource).'.js';
    }
    
    public function getPath(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POP_PRETTYPRINT_URL.'/js/'.$subpath.'libraries';
    }

    public function getJsobjects(array $resource)
    {
        $objects = array(
            self::RESOURCE_PRETTYPRINT => array(
                'PrettyPrint',
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
            case self::RESOURCE_PRETTYPRINT:
                $dependencies[] = [PoP_PrettyPrint_VendorJSResourceLoaderProcessor::class, PoP_PrettyPrint_VendorJSResourceLoaderProcessor::RESOURCE_EXTERNAL_CODEPRETTIFY];
                break;
        }

        return $dependencies;
    }
}


