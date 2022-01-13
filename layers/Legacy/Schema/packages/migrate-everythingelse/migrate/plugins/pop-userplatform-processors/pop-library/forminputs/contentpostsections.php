<?php
use PoP\Engine\FormInputs\MultipleSelectFormInput;
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class GD_FormInput_PostSections extends MultipleSelectFormInput
{
    public function getAllValues($label = null): array
    {
        $values = parent::getAllValues($label);

        foreach (PoP_Application_Utils::getContentpostsectionCats() as $cat) {
            $values[$cat] = HooksAPIFacade::getInstance()->applyFilters('GD_FormInput_PostSections:cat:name', gdGetCategoryname($cat), $cat);
        }

        return $values;
    }
}
