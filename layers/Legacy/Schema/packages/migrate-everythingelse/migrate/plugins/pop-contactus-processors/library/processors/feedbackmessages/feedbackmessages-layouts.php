<?php
use PoP\Translation\Facades\TranslationAPIFacade;

class PoP_ContactUs_Module_Processor_FeedbackMessageLayouts extends PoP_Module_Processor_FormFeedbackMessageLayoutsBase
{
    public const MODULE_LAYOUT_FEEDBACKMESSAGE_CONTACTUS = 'layout-feedbackmessage-contactus';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGE_CONTACTUS],
        );
    }

    public function getMessages(array $module, array &$props)
    {
        $ret = parent::getMessages($module, $props);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_FEEDBACKMESSAGE_CONTACTUS:
                $ret['success-header'] = TranslationAPIFacade::getInstance()->__('Message sent successfully!', 'pop-genericforms');
                $ret['success'] = TranslationAPIFacade::getInstance()->__('Thanks for contacting us, we will get in touch with you shortly.', 'pop-genericforms');
                $ret['empty-name'] = TranslationAPIFacade::getInstance()->__('Name is missing.', 'pop-genericforms');
                $ret['empty-email'] = TranslationAPIFacade::getInstance()->__('Email is missing or format is incorrect.', 'pop-genericforms');
                $ret['empty-message'] = TranslationAPIFacade::getInstance()->__('Message is missing.', 'pop-genericforms');
                break;
        }

        return $ret;
    }
}



