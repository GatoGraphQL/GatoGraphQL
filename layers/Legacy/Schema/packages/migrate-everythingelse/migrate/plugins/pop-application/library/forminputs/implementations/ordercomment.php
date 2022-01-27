<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\SchemaCommons\FormInputs\OrderFormInput;

class GD_FormInput_OrderComment extends OrderFormInput
{
    public function getAllValues($label = null): array
    {
        $values = parent::getAllValues($label);

        $values = array_merge(
            $values,
            array(
                'date|DESC' => TranslationAPIFacade::getInstance()->__('Latest added', 'pop-application'),
                'date|ASC' => TranslationAPIFacade::getInstance()->__('Earliest added', 'pop-application'),
            )
        );

        return $values;
    }
}
