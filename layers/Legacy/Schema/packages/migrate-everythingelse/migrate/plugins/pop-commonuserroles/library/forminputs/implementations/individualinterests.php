<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Engine\FormInputs\MultipleSelectFormInput;

class GD_FormInput_IndividualInterests extends MultipleSelectFormInput
{
    public function getAllValues($label = null): array
    {
        $values = parent::getAllValues($label);

        // The values here must be input from outside, to allow any potential website to add their own LinkCategories conveniently
        $values = array_merge(
            $values,
            HooksAPIFacade::getInstance()->applyFilters('wassup_individualinterests', array())
        );

        return $values;
    }
}
