<?php
use PoP\Engine\FormInputs\SelectFormInput;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_URE_FormInput_MemberStatus extends SelectFormInput
{
    /**
     * @return mixed[]
     */
    public function getAllValues(string $label = null): array
    {
        $values = parent::getAllValues($label);

        $values = array_merge(
            $values,
            array(
                GD_URE_METAVALUE_PROFILE_COMMUNITIES_MEMBERSTATUS_ACTIVE => TranslationAPIFacade::getInstance()->__('Active', 'ure-popprocessors'),
                GD_URE_METAVALUE_PROFILE_COMMUNITIES_MEMBERSTATUS_REJECTED => TranslationAPIFacade::getInstance()->__('Rejected', 'ure-popprocessors'),
            )
        );

        return $values;
    }
}
