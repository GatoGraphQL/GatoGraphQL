<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_FormFeedbackMessageLayoutsBase extends PoP_Module_Processor_FeedbackMessageLayoutsBase
{
    public function getMessages(array $componentVariation, array &$props)
    {
        $ret = parent::getMessages($componentVariation, $props);
        $ret['error-header'] = $this->getProp($componentVariation, $props, 'error-header');
        $ret['success-header'] = $this->getProp($componentVariation, $props, 'success-header');

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        $this->setProp($componentVariation, $props, 'error-header', TranslationAPIFacade::getInstance()->__('Oops, there were some problems:', 'pop-coreprocessors'));
        $this->setProp($componentVariation, $props, 'success-header', TranslationAPIFacade::getInstance()->__('Success!', 'pop-coreprocessors'));
        parent::initModelProps($componentVariation, $props);
    }
}
