<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_URE_Module_Processor_ProfileFeedbackMessageLayouts extends PoP_Module_Processor_FormFeedbackMessageLayoutsBase
{
    public final const COMPONENT_LAYOUT_FEEDBACKMESSAGE_UPDATEMYCOMMUNITIES = 'layout-feedbackmessage-updatemycommunities';
    public final const COMPONENT_LAYOUT_FEEDBACKMESSAGE_INVITENEWMEMBERS = 'layout-feedbackmessage-invitemembers';
    public final const COMPONENT_LAYOUT_FEEDBACKMESSAGE_EDITMEMBERSHIP = 'layout-feedbackmessage-editmembership';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_FEEDBACKMESSAGE_UPDATEMYCOMMUNITIES,
            self::COMPONENT_LAYOUT_FEEDBACKMESSAGE_INVITENEWMEMBERS,
            self::COMPONENT_LAYOUT_FEEDBACKMESSAGE_EDITMEMBERSHIP,
        );
    }

    public function getMessages(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getMessages($component, $props);

        switch ($component->name) {
            case self::COMPONENT_LAYOUT_FEEDBACKMESSAGE_UPDATEMYCOMMUNITIES:
                $ret['success-header'] = TranslationAPIFacade::getInstance()->__('Update successful!', 'ure-popprocessors');
                $ret['success'] = TranslationAPIFacade::getInstance()->__('Your changes have been saved.', 'ure-popprocessors');
                break;

            case self::COMPONENT_LAYOUT_FEEDBACKMESSAGE_INVITENEWMEMBERS:
                $ret['success-header'] = TranslationAPIFacade::getInstance()->__('Invite successful!', 'ure-popprocessors');
                break;

            case self::COMPONENT_LAYOUT_FEEDBACKMESSAGE_EDITMEMBERSHIP:
                $ret['success-header'] = TranslationAPIFacade::getInstance()->__('Status update successful!', 'ure-popprocessors');
                $ret['success'] = TranslationAPIFacade::getInstance()->__('Your changes have been saved.', 'ure-popprocessors');
                break;
        }

        return $ret;
    }
}



