<?php
namespace PoP\Engine;
use PoP\ComponentModel\GD_FormInput;

class GD_FormInput_Select extends GD_FormInput
{

    /**
     * Function to override
     */
    public function getAllValues($label = null)
    {
        if ($label) {
            return array('' => $label);
        }

        return array();
    }
    
    
    public function getSelectedValue()
    {
        if (is_null($this->selected)) {
            return null;
        }
    
        $all = $this->getAllValues();
        if ($this->isMultiple()) {
            $value = array();
            foreach ($this->selected as $selected) {
                $value[] = $all[$selected];
            }
        } else {
            $value = $all[$this->selected];
        }
        
        return $value;
    }
}
