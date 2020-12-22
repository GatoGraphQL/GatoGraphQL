<?php
namespace PoP\Engine;

class GD_FormInput_Order extends GD_FormInput_Select
{
    public function getValue(?array $source = null)
    {
        if ($value = parent::getValue($source)) {
            // There must be exactly 2 elements: orderby|order
            $elems = explode('|', $value);
            if (count($elems) == 2) {
                return array('orderby' => $elems[0], 'order' => $elems[1]);
            }
        }

        return null;
    }

    // function getOutputValue() {

    //     return parent::getValue(true);
    // }
}
