<?php
use PoP\Engine\FormInputs\MultipleSelectFormInput;

class GD_FormInput_MultiStance extends MultipleSelectFormInput
{
    public function getAllValues($label = null)
    {
        $values = parent::getAllValues($label);

        // The sections are received in this format:
        // array('taxonomy' => 'terms')
        // To print it in the forminput, it must be transformed to a list with this format:
        // array('taxonomy|term')
        // Allow to override these values: Allow TPPDebate to change "Pro" to "Pro TPP", etc
        $term_names = PoP_UserStance_PostNameUtils::getTermNames();
        foreach ($term_names as $term => $name) {
            $item = POP_USERSTANCE_TAXONOMY_STANCE.'|'.$term;
            $values[$item] = $name;
        }

        return $values;
    }
}
