<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\SchemaCommons\FormInputs\OrderFormInput;

class GD_FormInput_OrderTag extends OrderFormInput
{
    public function getAllValues($label = null): array
    {
        $values = parent::getAllValues($label);

        $values = array_merge(
            $values,
            array(
                'name|ASC' => TranslationAPIFacade::getInstance()->__('Name ascending', 'pop-application'),
                'name|DESC' => TranslationAPIFacade::getInstance()->__('Name descending', 'pop-application'),
                'count|DESC' => TranslationAPIFacade::getInstance()->__('Count highest', 'pop-application'),
                'count|ASC' => TranslationAPIFacade::getInstance()->__('Count lowest', 'pop-application'),
            )
        );

        return $values;
    }

    public function getDefaultValue(): mixed
    {
        return 'count|DESC';
    }
}
