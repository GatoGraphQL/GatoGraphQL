<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\Events\Facades\EventTypeAPIFacade;

abstract class PoP_Module_Processor_EventTypeaheadComponentFormInputsBase extends PoP_Module_Processor_PostTypeaheadComponentFormInputsBase
{
    protected function getThumbprintQuery(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getThumbprintQuery($component, $props);

        $eventTypeAPI = EventTypeAPIFacade::getInstance();
        $ret['custompost-types'] = [$eventTypeAPI->getEventCustomPostType()];

        return $ret;
    }

    protected function getPendingMsg(\PoP\ComponentModel\Component\Component $component)
    {
        return TranslationAPIFacade::getInstance()->__('Loading Events', 'em-popprocessors');
    }
    protected function getNotfoundMsg(\PoP\ComponentModel\Component\Component $component)
    {
        return TranslationAPIFacade::getInstance()->__('No Events found', 'em-popprocessors');
    }
}
