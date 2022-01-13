<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_LocationTypeaheadComponentFormInputsBase extends PoP_Module_Processor_PostTypeaheadComponentFormInputsBase
{
    protected function getThumbprintQuery(array $module, array &$props)
    {
        $ret = parent::getThumbprintQuery($module, $props);

        $pluginapi = PoP_Locations_APIFactory::getInstance();
        $ret['custompost-types'] = [$pluginapi->getLocationPostType()];

        return $ret;
    }

    public function getComponentTemplateResource(array $module)
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_LAYOUTLOCATION_TYPEAHEAD_COMPONENT];
    }
    protected function getValueKey(array $module, array &$props)
    {
        return 'name';
    }
    protected function getTokenizerKeys(array $module, array &$props)
    {
        return array('name', 'address');
    }

    protected function getPendingMsg(array $module)
    {
        return TranslationAPIFacade::getInstance()->__('Loading Locations', 'em-popprocessors');
    }
    protected function getNotfoundMsg(array $module)
    {
        return TranslationAPIFacade::getInstance()->__('No Locations found', 'em-popprocessors');
    }
}
