<?php

declare(strict_types=1);

namespace PoP\Engine\FormInputs;

use PoP\Engine\Constants\FormInputConstants;

trait BooleanFormInputTrait
{
    protected function getValueFromSource(array $source): mixed
    {
        // If it is not set, then return NULL, so that doing #formcomponentValue ignores value and proceeds to dbObject[dbObjectField]
        if (!isset($source[$this->getName()])) {
            return null;
        }

        if ($this->isMultiple()) {
            $ret = array();
            if ($values = $source[$this->getName()]) {
                // Watch out passing a string as REST endpoint arg when array expected
                if (!is_array($values)) {
                    $values = [$values];
                }
                foreach ($values as $value) {
                    $ret[] = ($value === FormInputConstants::BOOLSTRING_TRUE);
                }
            }

            return $ret;
        }

        // For the checkbox, the value is true not if its value in the request is true,
        // but if they key has been set at all (checked: sends the attribute. unchecked: sends nothing)
        // Hence, for checkbox, it will always be true at this stage.
        // For select, it could be true or false
        return ($source[$this->getName()] === FormInputConstants::BOOLSTRING_TRUE);
    }
}
