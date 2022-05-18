<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class UserStance_Module_Processor_StanceLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_Application_UserStance_TemplateResourceLoaderProcessor::class, PoP_Application_UserStance_TemplateResourceLoaderProcessor::RESOURCE_LAYOUTSTANCE];
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $componentVariation, array &$props): array
    {
        return array('catName');
    }

    public function getStanceTitle(array $componentVariation, array &$props)
    {
        return TranslationAPIFacade::getInstance()->__('Stance:', 'pop-userstance-processors');
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);
    
        $ret[GD_JS_TITLES]['stance'] = $this->getStanceTitle($componentVariation, $props);
        
        return $ret;
    }
}
