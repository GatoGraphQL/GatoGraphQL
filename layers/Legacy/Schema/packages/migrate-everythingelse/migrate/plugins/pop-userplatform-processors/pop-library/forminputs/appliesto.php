<?php
use PoP\Engine\FormInputs\MultipleSelectFormInput;

class GD_FormInput_AppliesTo extends MultipleSelectFormInput
{
    /**
     * @return mixed[]
     */
    public function getAllValues(string $label = null): array
    {
        $values = parent::getAllValues($label);

        // The values here must be input from outside, to allow any potential website to add their own LinkCategories conveniently
        $values = array_merge(
            $values,
            \PoP\Root\App::applyFilters('wassup_appliesto', array())
        );

        return $values;
    }
}
