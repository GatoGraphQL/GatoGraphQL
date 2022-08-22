<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_URE_FormInput_FilterMemberTags extends GD_URE_FormInput_MemberTags
{
    /**
     * @return mixed[]
     */
    public function getAllValues(string $label = null): array
    {
        $values = parent::getAllValues($label);
        
        // Add the 'none' value
        $values[GD_METAVALUE_NONE] = TranslationAPIFacade::getInstance()->__('(None)', 'ure-popprocessors');
        
        return $values;
    }
}
