<?php
use PoP\Translation\Facades\TranslationAPIFacade;

class GD_URE_FormInput_MultiAuthorRole extends \PoP\Engine\GD_FormInput_MultiSelect
{
    public function getAllValues($label = null)
    {
        $values = parent::getAllValues($label);
        
        if (defined('POP_COMMONUSERROLES_INITIALIZED')) {
            $values[GD_URE_ROLE_ORGANIZATION] = TranslationAPIFacade::getInstance()->__('Organizations', 'pop-userstance-processors');
            $values[GD_URE_ROLE_INDIVIDUAL] = TranslationAPIFacade::getInstance()->__('Individuals', 'pop-userstance-processors');
        }
        
        return $values;
    }
}
