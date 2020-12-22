<?php
use PoP\Translation\Facades\TranslationAPIFacade;

class GD_URE_FormInput_MemberPrivileges extends \PoP\Engine\GD_FormInput_MultiSelect
{
    public function getAllValues($label = null)
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
