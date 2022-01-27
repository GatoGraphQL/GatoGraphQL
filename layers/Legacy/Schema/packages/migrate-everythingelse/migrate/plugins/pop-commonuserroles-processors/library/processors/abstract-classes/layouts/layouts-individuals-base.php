<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class GD_URE_Custom_Module_Processor_ProfileIndividualLayoutsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoPTheme_Wassup_URE_TemplateResourceLoaderProcessor::class, PoPTheme_Wassup_URE_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_PROFILEINDIVIDUAL_DETAILS];
    }

    public function getDataFields(array $module, array &$props): array
    {
        return array('individualInterestsByName');
    }

    public function getLabelClass(array $module, array &$props)
    {
        return 'label-info';
    }

    public function getTitle(array $module, array &$props)
    {
        return TranslationAPIFacade::getInstance()->__('Interests', 'poptheme-wassup');
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $ret[GD_JS_CLASSES]['label'] = $this->getLabelClass($module, $props);
        $ret[GD_JS_TITLES] = array(
            'interests' => $this->getTitle($module, $props),
        );
        
        return $ret;
    }
}
