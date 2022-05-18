<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_ListFeedbackMessageLayoutsBase extends PoP_Module_Processor_FeedbackMessageLayoutsBase
{
    public function getMessages(array $componentVariation, array &$props)
    {
        $ret = parent::getMessages($componentVariation, $props);
        $ret['noresults'] = TranslationAPIFacade::getInstance()->__('No results', 'pop-coreprocessors');
        $ret['nomore'] = TranslationAPIFacade::getInstance()->__('No more results found', 'pop-coreprocessors');

        return $ret;
    }
}
