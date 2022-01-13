<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_FormFeedbackMessageLayoutsBase extends PoP_Module_Processor_FeedbackMessageLayoutsBase
{
    public function getMessages(array $module, array &$props)
    {
        $ret = parent::getMessages($module, $props);
        $ret['error-header'] = $this->getProp($module, $props, 'error-header');
        $ret['success-header'] = $this->getProp($module, $props, 'success-header');

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        $this->setProp($module, $props, 'error-header', TranslationAPIFacade::getInstance()->__('Oops, there were some problems:', 'pop-coreprocessors'));
        $this->setProp($module, $props, 'success-header', TranslationAPIFacade::getInstance()->__('Success!', 'pop-coreprocessors'));
        parent::initModelProps($module, $props);
    }
}
