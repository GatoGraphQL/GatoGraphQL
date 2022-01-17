<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchemaPRO\Events\Facades\EventTypeAPIFacade;

abstract class PoP_Module_Processor_EventTypeaheadComponentFormInputsBase extends PoP_Module_Processor_PostTypeaheadComponentFormInputsBase
{
    protected function getThumbprintQuery(array $module, array &$props)
    {
        $ret = parent::getThumbprintQuery($module, $props);

        $eventTypeAPI = EventTypeAPIFacade::getInstance();
        $ret['custompost-types'] = [$eventTypeAPI->getEventCustomPostType()];

        return $ret;
    }

    protected function getPendingMsg(array $module)
    {
        return TranslationAPIFacade::getInstance()->__('Loading Events', 'em-popprocessors');
    }
    protected function getNotfoundMsg(array $module)
    {
        return TranslationAPIFacade::getInstance()->__('No Events found', 'em-popprocessors');
    }
}
