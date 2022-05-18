<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_UserQuickLinkLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUTUSER_QUICKLINKS];
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $component, array &$props): array
    {
        return array('contactSmall');
    }

    public function getNocontactTitle(array $component, array &$props)
    {
        return TranslationAPIFacade::getInstance()->__('No contact channels', 'pop-coreprocessors');
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $ret[GD_JS_TITLES]['nocontact'] = $this->getNocontactTitle($component, $props);
        
        return $ret;
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);
        
        $this->addJsmethod($ret, 'doNothing', 'void-link');

        return $ret;
    }
}
