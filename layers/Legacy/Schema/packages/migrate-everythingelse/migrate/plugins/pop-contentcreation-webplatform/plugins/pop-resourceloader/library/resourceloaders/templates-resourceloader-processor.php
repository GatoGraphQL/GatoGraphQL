<?php

class PoP_ContentCreation_TemplateResourceLoaderProcessor extends PoP_TemplateResourceLoaderProcessor
{
    public final const RESOURCE_FORMINPUT_FEATUREDIMAGE_INNER = 'forminput_featuredimage_inner';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_FORMINPUT_FEATUREDIMAGE_INNER],
        ];
    }
    
    public function getTemplate(array $resource)
    {
        $templates = array(
            self::RESOURCE_FORMINPUT_FEATUREDIMAGE_INNER => POP_TEMPLATE_FORMINPUT_FEATUREDIMAGE_INNER,
        );
        return $templates[$resource[1]];
    }
    
    public function getVersion(array $resource)
    {
        return POP_CONTENTCREATIONWEBPLATFORM_VERSION;
    }
    
    public function getPath(array $resource)
    {
        return POP_CONTENTCREATIONWEBPLATFORM_URL.'/js/dist/templates';
    }
    
    public function getDir(array $resource)
    {
        return POP_CONTENTCREATIONWEBPLATFORM_DIR.'/js/dist/templates';
    }
    
    public function getDependencies(array $resource)
    {
        $dependencies = parent::getDependencies($resource);
    
        switch ($resource[1]) {
            case self::RESOURCE_FORMINPUT_FEATUREDIMAGE_INNER:
                $dependencies[] = [PoP_Forms_HandlebarsHelpersJSResourceLoaderProcessor::class, PoP_Forms_HandlebarsHelpersJSResourceLoaderProcessor::RESOURCE_HANDLEBARSHELPERS_FORMCOMPONENTS];
                break;
        }

        return $dependencies;
    }
}


