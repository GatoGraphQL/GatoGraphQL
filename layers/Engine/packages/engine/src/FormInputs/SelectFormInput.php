<?php

declare(strict_types=1);

namespace PoP\Engine\FormInputs;

use PoP\ComponentModel\FormInputs\FormInput;

class SelectFormInput extends FormInput
{
    /**
     * Function to override
     *
     * @return mixed[]
     */
    public function getAllValues(string $label = null): array
    {
        if ($label) {
            return array('' => $label);
        }

        return array();
    }


    public function getSelectedValue(): mixed
    {
        if ($this->selected === null) {
            return null;
        }

        $all = $this->getAllValues();
        if ($this->isMultiple()) {
            $value = array();
            $selected = (array)$this->selected;
            foreach ($selected as $selectedItem) {
                $value[] = $all[$selectedItem];
            }
        } else {
            $value = $all[$this->selected];
        }

        return $value;
    }
}
