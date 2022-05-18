<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_FormFeedbackMessageLayoutsBase extends PoP_Module_Processor_FeedbackMessageLayoutsBase
{
    public function getMessages(array $component, array &$props)
    {
        $ret = parent::getMessages($component, $props);
        $ret['error-header'] = $this->getProp($component, $props, 'error-header');
        $ret['success-header'] = $this->getProp($component, $props, 'success-header');

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        $this->setProp($component, $props, 'error-header', TranslationAPIFacade::getInstance()->__('Oops, there were some problems:', 'pop-coreprocessors'));
        $this->setProp($component, $props, 'success-header', TranslationAPIFacade::getInstance()->__('Success!', 'pop-coreprocessors'));
        parent::initModelProps($component, $props);
    }
}
