<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_VolunteerTagLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_ApplicationProcessors_TemplateResourceLoaderProcessor::class, PoP_ApplicationProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_VOLUNTEERTAG];
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $component, array &$props): array
    {
        return array('volunteersNeeded');
    }

    public function getTitle(array $component, array &$props)
    {
        return '<i class="fa fa-leaf fa-fw fa-lg"></i>'.TranslationAPIFacade::getInstance()->__('Volunteer!', 'poptheme-wassup');
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
    
        $ret[GD_JS_TITLES] = array(
            'volunteer' => $this->getTitle($component, $props)
        );
        
        return $ret;
    }
}
