<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_CheckpointMessageLayoutsBase extends PoP_Module_Processor_FeedbackMessageLayoutsBase
{
    public function getMessages(array $componentVariation, array &$props)
    {
        $ret = parent::getMessages($componentVariation, $props);
        $ret['error-header'] = TranslationAPIFacade::getInstance()->__('Oops, there were some problems:', 'pop-coreprocessors');
        return $ret;
    }
}
