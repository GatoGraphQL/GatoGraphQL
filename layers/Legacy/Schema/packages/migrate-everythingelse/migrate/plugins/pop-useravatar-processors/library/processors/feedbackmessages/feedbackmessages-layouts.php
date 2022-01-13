<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_UserAvatarProcessors_Module_Processor_UserFeedbackMessageLayouts extends PoP_Module_Processor_FormFeedbackMessageLayoutsBase
{
    public const MODULE_LAYOUT_FEEDBACKMESSAGE_USERAVATAR_UPDATE = 'layout-feedbackmessage-useravatar-update';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGE_USERAVATAR_UPDATE],
        );
    }

    public function getMessages(array $module, array &$props)
    {
        $ret = parent::getMessages($module, $props);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_FEEDBACKMESSAGE_USERAVATAR_UPDATE:
                $ret['success-header'] = TranslationAPIFacade::getInstance()->__('Picture updated successfully.', 'pop-useravatar-processors');
                $ret['success'] = TranslationAPIFacade::getInstance()->__('Such a good shot!', 'pop-useravatar-processors');
                break;
        }

        return $ret;
    }
}



