<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_CheckpointMessageLayoutsBase extends PoP_Module_Processor_FeedbackMessageLayoutsBase
{
    public function getMessages(array $module, array &$props)
    {
        $ret = parent::getMessages($module, $props);
        $ret['error-header'] = TranslationAPIFacade::getInstance()->__('Oops, there were some problems:', 'pop-coreprocessors');
        return $ret;
    }
}
