<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class GD_URE_Custom_Module_Processor_ProfileIndividualLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoPTheme_Wassup_URE_TemplateResourceLoaderProcessor::class, PoPTheme_Wassup_URE_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_PROFILEINDIVIDUAL_DETAILS];
    }

    /**
     * @todo Migrate from string to LeafComponentFieldNode
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafComponentFieldNode[]
     */
    public function getLeafComponentFieldNodes(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        return array('individualInterestsByName');
    }

    public function getLabelClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return 'label-info';
    }

    public function getTitle(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return TranslationAPIFacade::getInstance()->__('Interests', 'poptheme-wassup');
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $ret[GD_JS_CLASSES]['label'] = $this->getLabelClass($component, $props);
        $ret[GD_JS_TITLES] = array(
            'interests' => $this->getTitle($component, $props),
        );
        
        return $ret;
    }
}
