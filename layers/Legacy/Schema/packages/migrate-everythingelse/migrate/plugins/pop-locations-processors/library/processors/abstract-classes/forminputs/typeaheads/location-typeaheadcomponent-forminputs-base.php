<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_LocationTypeaheadComponentFormInputsBase extends PoP_Module_Processor_PostTypeaheadComponentFormInputsBase
{
    protected function getThumbprintQuery(array $component, array &$props)
    {
        $ret = parent::getThumbprintQuery($component, $props);

        $pluginapi = PoP_Locations_APIFactory::getInstance();
        $ret['custompost-types'] = [$pluginapi->getLocationPostType()];

        return $ret;
    }

    public function getComponentTemplateResource(array $component)
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_LAYOUTLOCATION_TYPEAHEAD_COMPONENT];
    }
    protected function getValueKey(array $component, array &$props)
    {
        return 'name';
    }
    protected function getTokenizerKeys(array $component, array &$props)
    {
        return array('name', 'address');
    }

    protected function getPendingMsg(array $component)
    {
        return TranslationAPIFacade::getInstance()->__('Loading Locations', 'em-popprocessors');
    }
    protected function getNotfoundMsg(array $component)
    {
        return TranslationAPIFacade::getInstance()->__('No Locations found', 'em-popprocessors');
    }
}
