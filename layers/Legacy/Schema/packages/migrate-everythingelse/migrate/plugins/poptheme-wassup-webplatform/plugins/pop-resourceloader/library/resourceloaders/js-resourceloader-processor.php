<?php

class PoPTheme_Wassup_ResourceLoaderProcessor extends PoP_JSResourceLoaderProcessor
{
    public final const RESOURCE_CUSTOMFUNCTIONS = 'custom-functions';
    public final const RESOURCE_CUSTOMPAGESECTIONMANAGER = 'custom-pagesection-manager';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_CUSTOMFUNCTIONS],
            [self::class, self::RESOURCE_CUSTOMPAGESECTIONMANAGER],
        ];
    }
    
    public function getFilename(array $resource)
    {
        $filenames = array(
            self::RESOURCE_CUSTOMFUNCTIONS => 'custom-functions',
            self::RESOURCE_CUSTOMPAGESECTIONMANAGER => 'custom-pagesection-manager',
        );
        if ($filename = $filenames[$resource[1]]) {
            return $filename;
        }

        return parent::getFilename($resource);
    }
    
    public function getVersion(array $resource)
    {
        return POPTHEME_WASSUPWEBPLATFORM_VERSION;
    }
    
    public function getDir(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POPTHEME_WASSUPWEBPLATFORM_DIR.'/js/'.$subpath.'libraries';
    }
    
    public function getAssetPath(array $resource)
    {
        return POPTHEME_WASSUPWEBPLATFORM_DIR.'/js/libraries/'.$this->getFilename($resource).'.js';
    }
    
    public function getPath(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POPTHEME_WASSUPWEBPLATFORM_URL.'/js/'.$subpath.'libraries';
    }

    public function getJsobjects(array $resource)
    {
        $objects = array(
            self::RESOURCE_CUSTOMFUNCTIONS => array(
                'CustomFunctions',
            ),
            self::RESOURCE_CUSTOMPAGESECTIONMANAGER => array(
                'CustomPageSectionManager',
            ),
        );
        if ($object = $objects[$resource[1]]) {
            return $object;
        }

        return parent::getJsobjects($resource);
    }
}


