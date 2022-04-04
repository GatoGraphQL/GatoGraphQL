<?php

class PoPTheme_Wassup_URE_TemplateResourceLoaderProcessor extends PoP_TemplateResourceLoaderProcessor
{
    public final const RESOURCE_LAYOUT_PROFILEINDIVIDUAL_DETAILS = 'layout_profileindividual_details';
    public final const RESOURCE_LAYOUT_PROFILEORGANIZATION_DETAILS = 'layout_profileorganization_details';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_LAYOUT_PROFILEINDIVIDUAL_DETAILS],
            [self::class, self::RESOURCE_LAYOUT_PROFILEORGANIZATION_DETAILS],
        ];
    }
    
    public function getTemplate(array $resource)
    {
        $templates = array(
            self::RESOURCE_LAYOUT_PROFILEINDIVIDUAL_DETAILS => POP_TEMPLATE_LAYOUT_PROFILEINDIVIDUAL_DETAILS,
            self::RESOURCE_LAYOUT_PROFILEORGANIZATION_DETAILS => POP_TEMPLATE_LAYOUT_PROFILEORGANIZATION_DETAILS,
        );
        return $templates[$resource[1]];
    }
    
    public function getVersion(array $resource)
    {
        return POP_COMMONUSERROLESWEBPLATFORM_VERSION;
    }
    
    public function getPath(array $resource)
    {
        return POP_COMMONUSERROLESWEBPLATFORM_URL.'/js/dist/templates';
    }
    
    public function getDir(array $resource)
    {
        return POP_COMMONUSERROLESWEBPLATFORM_DIR.'/js/dist/templates';
    }
    
    public function getDependencies(array $resource)
    {
        $dependencies = parent::getDependencies($resource);
    
        switch ($resource[1]) {
            case self::RESOURCE_LAYOUT_PROFILEINDIVIDUAL_DETAILS:
            case self::RESOURCE_LAYOUT_PROFILEORGANIZATION_DETAILS:
                $dependencies[] = [PoP_CoreProcessors_HandlebarsHelpersJSResourceLoaderProcessor::class, PoP_CoreProcessors_HandlebarsHelpersJSResourceLoaderProcessor::RESOURCE_HANDLEBARSHELPERS_LABELS];
                break;
        }

        return $dependencies;
    }
}


