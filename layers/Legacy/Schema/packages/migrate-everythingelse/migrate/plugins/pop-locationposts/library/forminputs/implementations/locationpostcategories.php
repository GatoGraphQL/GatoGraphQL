<?php
use PoP\Engine\FormInputs\MultipleSelectFormInput;
use PoP\Hooks\Facades\HooksAPIFacade;

class GD_FormInput_LocationPostCategories extends MultipleSelectFormInput
{
    public function getAllValues($label = null): array
    {
        $values = parent::getAllValues($label);

        // The values here must be input from outside, to allow any potential website to add their own LinkCategories conveniently
        $values = array_merge(
            $values,
            HooksAPIFacade::getInstance()->applyFilters('wassup_locationpostcategories', array())
        );

        return $values;
    }
}
