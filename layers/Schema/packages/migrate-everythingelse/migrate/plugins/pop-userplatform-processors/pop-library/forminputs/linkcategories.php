<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class GD_FormInput_LinkCategories extends \PoP\Engine\GD_FormInput_MultiSelect
{
    public function getAllValues($label = null)
    {
        $values = parent::getAllValues($label);
        
        // The values here must be input from outside, to allow any potential website to add their own LinkCategories conveniently
        $values = array_merge(
            $values,
            HooksAPIFacade::getInstance()->applyFilters('wassup_linkcategories', array())
        );
        
        return $values;
    }
}
