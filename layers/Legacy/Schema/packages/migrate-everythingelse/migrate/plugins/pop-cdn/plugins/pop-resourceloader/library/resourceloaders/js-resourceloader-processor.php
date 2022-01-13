<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_CDN_JSResourceLoaderProcessor extends PoP_JSResourceLoaderProcessor
{
    public const RESOURCE_CDN = 'cdn';
    
    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_CDN],
        ];
    }
    
    public function getFilename(array $resource)
    {
        $filenames = array(
            self::RESOURCE_CDN => 'cdn',
        );
        if ($filename = $filenames[$resource[1]]) {
            return $filename;
        }

        return parent::getFilename($resource);
    }
    
    public function getVersion(array $resource)
    {
        return POP_CDN_VERSION;
    }
    
    public function getDir(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POP_CDN_DIR.'/js/'.$subpath.'libraries';
    }
    
    public function getAssetPath(array $resource)
    {
        return POP_CDN_DIR.'/js/libraries/'.$this->getFilename($resource).'.js';
    }
    
    public function getPath(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POP_CDN_URL.'/js/'.$subpath.'libraries';
    }

    public function getJsobjects(array $resource)
    {
        $objects = array(
            self::RESOURCE_CDN => array(
                'CDN',
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
            case self::RESOURCE_CDN:
                // All templates depend on the handlebars runtime. Allow plugins to add their own dependencies
                if ($cdn_dependencies = \PoP\Root\App::getHookManager()->applyFilters(
                    'PoP_CDN_ResourceLoaderProcessor:dependencies',
                    array(
                    )
                )
                ) {
                    $dependencies = array_merge(
                        $dependencies,
                        $cdn_dependencies
                    );
                }
                break;
        }

        return $dependencies;
    }
}


