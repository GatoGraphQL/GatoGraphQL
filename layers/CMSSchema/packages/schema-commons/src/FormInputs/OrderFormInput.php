<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\FormInputs;

use PoP\Engine\FormInputs\SelectFormInput;

class OrderFormInput extends SelectFormInput
{
    public final const SEPARATOR = '|';

    public function getValue(?array $source = null): mixed
    {
        if ($value = parent::getValue($source)) {
            // There must be exactly 2 elements: orderby|order
            $elems = explode(self::SEPARATOR, $value);
            if (count($elems) >= 2) {
                return array('orderby' => $elems[0], 'order' => $elems[1]);
            }
            // Passing only 1 item also works: orderby
            return array('orderby' => $elems[0]);
        }

        return null;
    }

    // function getOutputValue() {

    //     return parent::getValue(true);
    // }
}
