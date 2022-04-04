<?php

class PoP_BootstrapProcessors_CarouselResourceLoaderProcessor extends PoP_JSResourceLoaderProcessor
{
    public final const RESOURCE_BOOTSTRAPCAROUSEL = 'bootstrap-carousel';
    public final const RESOURCE_BOOTSTRAPCAROUSELSTATIC = 'bootstrap-carousel-static';
    public final const RESOURCE_BOOTSTRAPCAROUSELAUTOMATIC = 'bootstrap-carousel-automatic';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_BOOTSTRAPCAROUSEL],
            [self::class, self::RESOURCE_BOOTSTRAPCAROUSELSTATIC],
            [self::class, self::RESOURCE_BOOTSTRAPCAROUSELAUTOMATIC],
        ];
    }
    
    public function getFilename(array $resource)
    {
        $filenames = array(
            self::RESOURCE_BOOTSTRAPCAROUSEL => 'bootstrap-carousel',
            self::RESOURCE_BOOTSTRAPCAROUSELSTATIC => 'bootstrap-carousel-static',
            self::RESOURCE_BOOTSTRAPCAROUSELAUTOMATIC => 'bootstrap-carousel-automatic',
        );
        if ($filename = $filenames[$resource[1]]) {
            return $filename;
        }

        return parent::getFilename($resource);
    }
    
    public function getVersion(array $resource)
    {
        return POP_BOOTSTRAPCOLLECTIONWEBPLATFORM_VERSION;
    }
    
    public function getDir(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POP_BOOTSTRAPCOLLECTIONWEBPLATFORM_DIR.'/js/'.$subpath.'libraries/carousel';
    }
    
    public function getAssetPath(array $resource)
    {
        return POP_BOOTSTRAPCOLLECTIONWEBPLATFORM_DIR.'/js/libraries/carousel/'.$this->getFilename($resource).'.js';
    }
    
    public function getPath(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POP_BOOTSTRAPCOLLECTIONWEBPLATFORM_URL.'/js/'.$subpath.'libraries/carousel';
    }

    public function getJsobjects(array $resource)
    {
        $objects = array(
            self::RESOURCE_BOOTSTRAPCAROUSEL => array(
                'BootstrapCarousel',
                'BootstrapCarouselControls',
            ),
            self::RESOURCE_BOOTSTRAPCAROUSELSTATIC => array(
                'BootstrapCarouselStatic',
            ),
            self::RESOURCE_BOOTSTRAPCAROUSELAUTOMATIC => array(
                'BootstrapCarouselAutomatic',
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
            case self::RESOURCE_BOOTSTRAPCAROUSEL:
            case self::RESOURCE_BOOTSTRAPCAROUSELSTATIC:
            case self::RESOURCE_BOOTSTRAPCAROUSELAUTOMATIC:
                $dependencies[] = [PoP_BootstrapWebPlatform_VendorJSResourceLoaderProcessor::class, PoP_BootstrapWebPlatform_VendorJSResourceLoaderProcessor::RESOURCE_EXTERNAL_BOOTSTRAP];
                break;
        }

        return $dependencies;
    }
}


