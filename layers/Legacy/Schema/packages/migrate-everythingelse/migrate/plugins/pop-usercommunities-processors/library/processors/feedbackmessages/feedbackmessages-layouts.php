<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_URE_Module_Processor_ProfileFeedbackMessageLayouts extends PoP_Module_Processor_FormFeedbackMessageLayoutsBase
{
    public const MODULE_LAYOUT_FEEDBACKMESSAGE_UPDATEMYCOMMUNITIES = 'layout-feedbackmessage-updatemycommunities';
    public const MODULE_LAYOUT_FEEDBACKMESSAGE_INVITENEWMEMBERS = 'layout-feedbackmessage-invitemembers';
    public const MODULE_LAYOUT_FEEDBACKMESSAGE_EDITMEMBERSHIP = 'layout-feedbackmessage-editmembership';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGE_UPDATEMYCOMMUNITIES],
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGE_INVITENEWMEMBERS],
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGE_EDITMEMBERSHIP],
        );
    }

    public function getMessages(array $module, array &$props)
    {
        $ret = parent::getMessages($module, $props);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_FEEDBACKMESSAGE_UPDATEMYCOMMUNITIES:
                $ret['success-header'] = TranslationAPIFacade::getInstance()->__('Update successful!', 'ure-popprocessors');
                $ret['success'] = TranslationAPIFacade::getInstance()->__('Your changes have been saved.', 'ure-popprocessors');
                break;

            case self::MODULE_LAYOUT_FEEDBACKMESSAGE_INVITENEWMEMBERS:
                $ret['success-header'] = TranslationAPIFacade::getInstance()->__('Invite successful!', 'ure-popprocessors');
                break;

            case self::MODULE_LAYOUT_FEEDBACKMESSAGE_EDITMEMBERSHIP:
                $ret['success-header'] = TranslationAPIFacade::getInstance()->__('Status update successful!', 'ure-popprocessors');
                $ret['success'] = TranslationAPIFacade::getInstance()->__('Your changes have been saved.', 'ure-popprocessors');
                break;
        }

        return $ret;
    }
}



