<?php
use PoP\Engine\FormInputs\MultipleSelectFormInput;
use PoP\Translation\Facades\TranslationAPIFacade;

class GD_URE_FormInput_MemberPrivileges extends MultipleSelectFormInput
{
    public function getAllValues($label = null): array
    {
        $values = parent::getAllValues($label);

        $values = array_merge(
            $values,
            array(
                GD_URE_METAVALUE_PROFILE_COMMUNITIES_MEMBERPRIVILEGES_CONTRIBUTECONTENT => TranslationAPIFacade::getInstance()->__('Contribute content', 'ure-popprocessors')
            )
        );

        return $values;
    }
}
