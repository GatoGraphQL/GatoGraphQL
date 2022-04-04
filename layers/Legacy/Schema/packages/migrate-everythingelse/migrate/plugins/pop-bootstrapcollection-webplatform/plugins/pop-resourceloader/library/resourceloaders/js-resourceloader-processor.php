<?php

class PoP_CoreProcessors_Bootstrap_ResourceLoaderProcessor extends PoP_JSResourceLoaderProcessor
{
    public final const RESOURCE_BOOTSTRAPCONTENT = 'bootstrap-content';
    public final const RESOURCE_BOOTSTRAPEMBED = 'bootstrap-embed';
    public final const RESOURCE_BOOTSTRAPFUNCTIONS = 'bootstrap-functions';
    public final const RESOURCE_BOOTSTRAPTYPEAHEAD = 'bootstrap-typeahead';
    public final const RESOURCE_BOOTSTRAPHOOKS = 'bootstrap-hooks';
    public final const RESOURCE_BOOTSTRAPINPUT = 'bootstrap-input';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_BOOTSTRAPCONTENT],
            [self::class, self::RESOURCE_BOOTSTRAPEMBED],
            [self::class, self::RESOURCE_BOOTSTRAPFUNCTIONS],
            [self::class, self::RESOURCE_BOOTSTRAPTYPEAHEAD],
            [self::class, self::RESOURCE_BOOTSTRAPHOOKS],
            [self::class, self::RESOURCE_BOOTSTRAPINPUT],
        ];
    }
    
    public function getFilename(array $resource)
    {
        $filenames = array(
            self::RESOURCE_BOOTSTRAPCONTENT => 'bootstrap-content',
            self::RESOURCE_BOOTSTRAPEMBED => 'bootstrap-embed',
            self::RESOURCE_BOOTSTRAPFUNCTIONS => 'bootstrap-functions',
            self::RESOURCE_BOOTSTRAPTYPEAHEAD => 'bootstrap-typeahead',
            self::RESOURCE_BOOTSTRAPHOOKS => 'bootstrap-hooks',
            self::RESOURCE_BOOTSTRAPINPUT => 'bootstrap-input',
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
        return POP_BOOTSTRAPCOLLECTIONWEBPLATFORM_DIR.'/js/'.$subpath.'libraries';
    }
    
    public function getAssetPath(array $resource)
    {
        return POP_BOOTSTRAPCOLLECTIONWEBPLATFORM_DIR.'/js/libraries/'.$this->getFilename($resource).'.js';
    }
    
    public function getPath(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POP_BOOTSTRAPCOLLECTIONWEBPLATFORM_URL.'/js/'.$subpath.'libraries';
    }

    public function getJsobjects(array $resource)
    {
        $objects = array(
            self::RESOURCE_BOOTSTRAPCONTENT => array(
                'BootstrapContent',
            ),
            self::RESOURCE_BOOTSTRAPEMBED => array(
                'BootstrapEmbed',
            ),
            self::RESOURCE_BOOTSTRAPFUNCTIONS => array(
                'BootstrapFunctions',
            ),
            self::RESOURCE_BOOTSTRAPTYPEAHEAD => array(
                'BootstrapTypeahead',
            ),
            self::RESOURCE_BOOTSTRAPHOOKS => array(
                'BootstrapHooks',
            ),
            self::RESOURCE_BOOTSTRAPINPUT => array(
                'BootstrapInput',
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
         // case self::RESOURCE_BOOTSTRAPCONTENT:
         // case self::RESOURCE_BOOTSTRAPEMBED:
            case self::RESOURCE_BOOTSTRAPFUNCTIONS:
                // case self::RESOURCE_BOOTSTRAPTYPEAHEAD:
                // case self::RESOURCE_BOOTSTRAPHOOKS:
                // case self::RESOURCE_BOOTSTRAPINPUT:

                $dependencies[] = [PoP_BootstrapWebPlatform_VendorJSResourceLoaderProcessor::class, PoP_BootstrapWebPlatform_VendorJSResourceLoaderProcessor::RESOURCE_EXTERNAL_BOOTSTRAP];
                break;
        }

        return $dependencies;
    }
}


