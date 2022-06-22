<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_LocationTypeaheadComponentFormInputsBase extends PoP_Module_Processor_PostTypeaheadComponentFormInputsBase
{
    protected function getThumbprintQuery(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getThumbprintQuery($component, $props);

        $pluginapi = PoP_Locations_APIFactory::getInstance();
        $ret['custompost-types'] = [$pluginapi->getLocationPostType()];

        return $ret;
    }

    public function getComponentTemplateResource(\PoP\ComponentModel\Component\Component $component)
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_LAYOUTLOCATION_TYPEAHEAD_COMPONENT];
    }
    protected function getValueKey(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return 'name';
    }
    protected function getTokenizerKeys(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return array('name', 'address');
    }

    protected function getPendingMsg(\PoP\ComponentModel\Component\Component $component)
    {
        return TranslationAPIFacade::getInstance()->__('Loading Locations', 'em-popprocessors');
    }
    protected function getNotfoundMsg(\PoP\ComponentModel\Component\Component $component)
    {
        return TranslationAPIFacade::getInstance()->__('No Locations found', 'em-popprocessors');
    }
}
