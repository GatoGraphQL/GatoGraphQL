<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_CreateLocationFeedbackMessageLayouts extends PoP_Module_Processor_FormFeedbackMessageLayoutsBase
{
    public const MODULE_LAYOUT_FEEDBACKMESSAGE_CREATELOCATION = 'layout-feedbackmessage-createlocation';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGE_CREATELOCATION],
        );
    }

    public function getMessages(array $module, array &$props)
    {
        $ret = parent::getMessages($module, $props);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_FEEDBACKMESSAGE_CREATELOCATION:
                $ret['success-header'] = TranslationAPIFacade::getInstance()->__('Location added successfully!', 'em-popprocessors');
                $ret['success'] = TranslationAPIFacade::getInstance()->__('More locations to add?', 'em-popprocessors');
                $ret['empty-town'] = TranslationAPIFacade::getInstance()->__('City is missing.', 'em-popprocessors');
                break;
        }

        return $ret;
    }
}



