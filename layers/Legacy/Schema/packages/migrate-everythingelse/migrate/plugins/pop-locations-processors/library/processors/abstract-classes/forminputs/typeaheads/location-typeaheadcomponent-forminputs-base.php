<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_LocationTypeaheadComponentFormInputsBase extends PoP_Module_Processor_PostTypeaheadComponentFormInputsBase
{
    protected function getThumbprintQuery(array $componentVariation, array &$props)
    {
        $ret = parent::getThumbprintQuery($componentVariation, $props);

        $pluginapi = PoP_Locations_APIFactory::getInstance();
        $ret['custompost-types'] = [$pluginapi->getLocationPostType()];

        return $ret;
    }

    public function getComponentTemplateResource(array $componentVariation)
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_LAYOUTLOCATION_TYPEAHEAD_COMPONENT];
    }
    protected function getValueKey(array $componentVariation, array &$props)
    {
        return 'name';
    }
    protected function getTokenizerKeys(array $componentVariation, array &$props)
    {
        return array('name', 'address');
    }

    protected function getPendingMsg(array $componentVariation)
    {
        return TranslationAPIFacade::getInstance()->__('Loading Locations', 'em-popprocessors');
    }
    protected function getNotfoundMsg(array $componentVariation)
    {
        return TranslationAPIFacade::getInstance()->__('No Locations found', 'em-popprocessors');
    }
}
