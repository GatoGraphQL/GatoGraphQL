<?php
use PoP\Translation\Facades\TranslationAPIFacade;

abstract class PoP_Module_Processor_UserQuickLinkLayoutsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUTUSER_QUICKLINKS];
    }

    public function getDataFields(array $module, array &$props): array
    {
        return array('contactSmall');
    }

    public function getNocontactTitle(array $module, array &$props)
    {
        return TranslationAPIFacade::getInstance()->__('No contact channels', 'pop-coreprocessors');
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $ret[GD_JS_TITLES]['nocontact'] = $this->getNocontactTitle($module, $props);
        
        return $ret;
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);
        
        $this->addJsmethod($ret, 'doNothing', 'void-link');

        return $ret;
    }
}
