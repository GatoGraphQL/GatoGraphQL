<?php
use PoP\Engine\FormInputs\MultipleSelectFormInput;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_URE_FormInput_MemberTags extends MultipleSelectFormInput
{
    public function getAllValues($label = null): array
    {
        $values = parent::getAllValues($label);

        $values = array_merge(
            $values,
            array(
                GD_URE_METAVALUE_PROFILE_COMMUNITIES_MEMBERTAGS_MEMBER => TranslationAPIFacade::getInstance()->__('Member', 'ure-popprocessors'),
                'staff' => TranslationAPIFacade::getInstance()->__('Staff', 'ure-popprocessors'),
                'volunteer' => TranslationAPIFacade::getInstance()->__('Volunteer', 'ure-popprocessors'),
                'student' => TranslationAPIFacade::getInstance()->__('Student', 'ure-popprocessors'),
                'teacher/lecturer' => TranslationAPIFacade::getInstance()->__('Teacher/Lecturer', 'ure-popprocessors'),
                'unknown' => TranslationAPIFacade::getInstance()->__('Unknown', 'ure-popprocessors'),
            )
        );

        return $values;
    }
}
