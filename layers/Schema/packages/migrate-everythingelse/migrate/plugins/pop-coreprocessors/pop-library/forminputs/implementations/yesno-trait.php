<?php
use PoP\Engine\FormInputs\BooleanFormInputTrait;
use PoP\Translation\Facades\TranslationAPIFacade;

trait GD_FormInput_YesNoTrait
{
    use BooleanFormInputTrait;

    public function getAllValues($label = null)
    {
        $values = parent::getAllValues($label);

        // Instead of using true/false values, use empty/non-empty string, because the booleans cannot be keys of an array (it converts them to positions/numbers and screws up everything)
        $values = array_merge(
            $values,
            array(
                POP_BOOLSTRING_TRUE => TranslationAPIFacade::getInstance()->__('Yes', 'pop-coreprocessors'),
                POP_BOOLSTRING_FALSE => TranslationAPIFacade::getInstance()->__('No', 'pop-coreprocessors'),
            )
        );

        return $values;
    }
}
