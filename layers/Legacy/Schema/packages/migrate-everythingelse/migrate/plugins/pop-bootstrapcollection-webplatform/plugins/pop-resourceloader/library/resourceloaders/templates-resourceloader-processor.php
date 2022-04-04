<?php

class PoP_BootstrapCollectionWebPlatform_TemplateResourceLoaderProcessor extends PoP_TemplateResourceLoaderProcessor
{
    public final const RESOURCE_ALERT = 'alert';
    public final const RESOURCE_CAROUSEL = 'carousel';
    public final const RESOURCE_CAROUSEL_CONTROLS = 'carousel_controls';
    public final const RESOURCE_CAROUSEL_INNER = 'carousel_inner';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_ALERT],
            [self::class, self::RESOURCE_CAROUSEL],
            [self::class, self::RESOURCE_CAROUSEL_CONTROLS],
            [self::class, self::RESOURCE_CAROUSEL_INNER],
        ];
    }
    
    public function getTemplate(array $resource)
    {
        $templates = array(
            self::RESOURCE_ALERT => POP_TEMPLATE_ALERT,
            self::RESOURCE_CAROUSEL => POP_TEMPLATE_CAROUSEL,
            self::RESOURCE_CAROUSEL_CONTROLS => POP_TEMPLATE_CAROUSEL_CONTROLS,
            self::RESOURCE_CAROUSEL_INNER => POP_TEMPLATE_CAROUSEL_INNER,
        );
        return $templates[$resource[1]];
    }
    
    public function getVersion(array $resource)
    {
        return POP_BOOTSTRAPCOLLECTIONWEBPLATFORM_VERSION;
    }
    
    public function getPath(array $resource)
    {
        return POP_BOOTSTRAPCOLLECTIONWEBPLATFORM_URL.'/js/dist/templates';
    }
    
    public function getDir(array $resource)
    {
        return POP_BOOTSTRAPCOLLECTIONWEBPLATFORM_DIR.'/js/dist/templates';
    }
    
    public function getDependencies(array $resource)
    {
        $dependencies = parent::getDependencies($resource);
    
        switch ($resource[1]) {
            case self::RESOURCE_CAROUSEL_INNER:
                $dependencies[] = [PoP_CoreProcessors_HandlebarsHelpersJSResourceLoaderProcessor::class, PoP_CoreProcessors_HandlebarsHelpersJSResourceLoaderProcessor::RESOURCE_HANDLEBARSHELPERS_MOD];
                break;
        }

        return $dependencies;
    }
}


