<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class UserStance_Module_Processor_StanceLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_Application_UserStance_TemplateResourceLoaderProcessor::class, PoP_Application_UserStance_TemplateResourceLoaderProcessor::RESOURCE_LAYOUTSTANCE];
    }

    /**
     * @todo Migrate from string to LeafComponentField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafComponentField[]
     */
    public function getLeafComponentFields(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        return array('catName');
    }

    public function getStanceTitle(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return TranslationAPIFacade::getInstance()->__('Stance:', 'pop-userstance-processors');
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);
    
        $ret[GD_JS_TITLES]['stance'] = $this->getStanceTitle($component, $props);
        
        return $ret;
    }
}
