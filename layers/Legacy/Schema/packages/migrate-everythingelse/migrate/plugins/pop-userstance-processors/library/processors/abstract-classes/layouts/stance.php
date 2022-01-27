<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class UserStance_Module_Processor_StanceLayoutsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_Application_UserStance_TemplateResourceLoaderProcessor::class, PoP_Application_UserStance_TemplateResourceLoaderProcessor::RESOURCE_LAYOUTSTANCE];
    }

    public function getDataFields(array $module, array &$props): array
    {
        return array('catName');
    }

    public function getStanceTitle(array $module, array &$props)
    {
        return TranslationAPIFacade::getInstance()->__('Stance:', 'pop-userstance-processors');
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);
    
        $ret[GD_JS_TITLES]['stance'] = $this->getStanceTitle($module, $props);
        
        return $ret;
    }
}
