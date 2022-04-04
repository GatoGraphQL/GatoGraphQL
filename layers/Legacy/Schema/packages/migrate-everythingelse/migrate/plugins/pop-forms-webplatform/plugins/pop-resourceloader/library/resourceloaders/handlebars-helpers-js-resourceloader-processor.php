<?php

class PoP_Forms_HandlebarsHelpersJSResourceLoaderProcessor extends PoP_HandlebarsHelpersJSResourceLoaderProcessor
{
    public final const RESOURCE_HANDLEBARSHELPERS_FORMCOMPONENTS = 'helpers-handlebars-formcomponents';
    public final const RESOURCE_HANDLEBARSHELPERS_FORMATVALUE = 'handlebars-helpers-formatvalue';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_HANDLEBARSHELPERS_FORMCOMPONENTS],
            [self::class, self::RESOURCE_HANDLEBARSHELPERS_FORMATVALUE],
        ];
    }
    
    public function getFilename(array $resource)
    {
        $filenames = array(
            self::RESOURCE_HANDLEBARSHELPERS_FORMCOMPONENTS => 'formcomponents',
            self::RESOURCE_HANDLEBARSHELPERS_FORMATVALUE => 'formatvalue',
        );
        if ($filename = $filenames[$resource[1]]) {
            return $filename;
        }

        return parent::getFilename($resource);
    }
    
    public function getVersion(array $resource)
    {
        return POP_FORMSWEBPLATFORM_VERSION;
    }
    
    public function getDir(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POP_FORMSWEBPLATFORM_DIR.'/js/'.$subpath.'libraries/handlebars-helpers';
    }
    
    public function getAssetPath(array $resource)
    {
        return POP_FORMSWEBPLATFORM_DIR.'/js/libraries/handlebars-helpers/'.$this->getFilename($resource).'.js';
    }
    
    public function getPath(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POP_FORMSWEBPLATFORM_URL.'/js/'.$subpath.'libraries/handlebars-helpers';
    }
}


