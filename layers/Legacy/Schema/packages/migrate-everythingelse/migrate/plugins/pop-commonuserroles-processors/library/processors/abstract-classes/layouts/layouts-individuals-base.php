<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class GD_URE_Custom_Module_Processor_ProfileIndividualLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoPTheme_Wassup_URE_TemplateResourceLoaderProcessor::class, PoPTheme_Wassup_URE_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_PROFILEINDIVIDUAL_DETAILS];
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $componentVariation, array &$props): array
    {
        return array('individualInterestsByName');
    }

    public function getLabelClass(array $componentVariation, array &$props)
    {
        return 'label-info';
    }

    public function getTitle(array $componentVariation, array &$props)
    {
        return TranslationAPIFacade::getInstance()->__('Interests', 'poptheme-wassup');
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $ret[GD_JS_CLASSES]['label'] = $this->getLabelClass($componentVariation, $props);
        $ret[GD_JS_TITLES] = array(
            'interests' => $this->getTitle($componentVariation, $props),
        );
        
        return $ret;
    }
}
