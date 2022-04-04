<?php

class PoP_BootstrapWebPlatform_TemplateResourceLoaderProcessor extends PoP_TemplateResourceLoaderProcessor
{
    public final const RESOURCE_BOOTSTRAPCOMPONENT_CAROUSEL = 'bootstrapcomponent_carousel';
    public final const RESOURCE_BOOTSTRAPCOMPONENT_COLLAPSEPANELGROUP = 'bootstrapcomponent_collapsepanelgroup';
    public final const RESOURCE_BOOTSTRAPCOMPONENT_MODAL = 'bootstrapcomponent_modal';
    public final const RESOURCE_BOOTSTRAPCOMPONENT_TABPANEL = 'bootstrapcomponent_tabpanel';
    public final const RESOURCE_BOOTSTRAPCOMPONENT_VIEWCOMPONENT = 'bootstrapcomponent_viewcomponent';
    public final const RESOURCE_PAGESECTION_MODAL = 'pagesection_modal';
    public final const RESOURCE_PAGESECTION_PAGETAB = 'pagesection_pagetab';
    public final const RESOURCE_PAGESECTION_TABPANE = 'pagesection_tabpane';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_BOOTSTRAPCOMPONENT_CAROUSEL],
            [self::class, self::RESOURCE_BOOTSTRAPCOMPONENT_COLLAPSEPANELGROUP],
            [self::class, self::RESOURCE_BOOTSTRAPCOMPONENT_MODAL],
            [self::class, self::RESOURCE_BOOTSTRAPCOMPONENT_TABPANEL],
            [self::class, self::RESOURCE_BOOTSTRAPCOMPONENT_VIEWCOMPONENT],
            [self::class, self::RESOURCE_PAGESECTION_MODAL],
            [self::class, self::RESOURCE_PAGESECTION_PAGETAB],
            [self::class, self::RESOURCE_PAGESECTION_TABPANE],
        ];
    }
    
    public function getTemplate(array $resource)
    {
        $templates = array(
            self::RESOURCE_BOOTSTRAPCOMPONENT_CAROUSEL => POP_TEMPLATE_BOOTSTRAPCOMPONENT_CAROUSEL,
            self::RESOURCE_BOOTSTRAPCOMPONENT_COLLAPSEPANELGROUP => POP_TEMPLATE_BOOTSTRAPCOMPONENT_COLLAPSEPANELGROUP,
            self::RESOURCE_BOOTSTRAPCOMPONENT_MODAL => POP_TEMPLATE_BOOTSTRAPCOMPONENT_MODAL,
            self::RESOURCE_BOOTSTRAPCOMPONENT_TABPANEL => POP_TEMPLATE_BOOTSTRAPCOMPONENT_TABPANEL,
            self::RESOURCE_BOOTSTRAPCOMPONENT_VIEWCOMPONENT => POP_TEMPLATE_BOOTSTRAPCOMPONENT_VIEWCOMPONENT,
            self::RESOURCE_PAGESECTION_MODAL => POP_TEMPLATE_PAGESECTION_MODAL,
            self::RESOURCE_PAGESECTION_PAGETAB => POP_TEMPLATE_PAGESECTION_PAGETAB,
            self::RESOURCE_PAGESECTION_TABPANE => POP_TEMPLATE_PAGESECTION_TABPANE,
        );
        return $templates[$resource[1]];
    }
    
    public function getVersion(array $resource)
    {
        return POP_BOOTSTRAPWEBPLATFORM_VERSION;
    }
    
    public function getPath(array $resource)
    {
        return POP_BOOTSTRAPWEBPLATFORM_URL.'/js/dist/templates';
    }
    
    public function getDir(array $resource)
    {
        return POP_BOOTSTRAPWEBPLATFORM_DIR.'/js/dist/templates';
    }

    public function getDependencies(array $resource)
    {
        $dependencies = parent::getDependencies($resource);
    
        switch ($resource[1]) {
            case self::RESOURCE_BOOTSTRAPCOMPONENT_CAROUSEL:
            case self::RESOURCE_BOOTSTRAPCOMPONENT_COLLAPSEPANELGROUP:
            case self::RESOURCE_BOOTSTRAPCOMPONENT_TABPANEL:
                $dependencies[] = [PoP_CoreProcessors_HandlebarsHelpersJSResourceLoaderProcessor::class, PoP_CoreProcessors_HandlebarsHelpersJSResourceLoaderProcessor::RESOURCE_HANDLEBARSHELPERS_COMPARE];
                break;
        }

        switch ($resource[1]) {
            case self::RESOURCE_BOOTSTRAPCOMPONENT_CAROUSEL:
                $dependencies[] = [PoP_CoreProcessors_HandlebarsHelpersJSResourceLoaderProcessor::class, PoP_CoreProcessors_HandlebarsHelpersJSResourceLoaderProcessor::RESOURCE_HANDLEBARSHELPERS_OPERATORS];
                break;
        }

        return $dependencies;
    }
}


