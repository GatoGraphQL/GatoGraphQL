<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSchema\SchemaCommons\FormInputs\OrderFormInput;

class GD_FormInput_OrderUser extends OrderFormInput
{
    public function getAllValues($label = null): array
    {
        $values = parent::getAllValues($label);

        $values = array_merge(
            $values,
            array(
                'display_name|ASC' => TranslationAPIFacade::getInstance()->__('Name ascending', 'pop-application'),
                'display_name|DESC' => TranslationAPIFacade::getInstance()->__('Name descending', 'pop-application'),
                'post_count|DESC' => TranslationAPIFacade::getInstance()->__('Most active', 'pop-application'),
                'post_count|ASC' => TranslationAPIFacade::getInstance()->__('Less active', 'pop-application'),
                'registered|DESC' => TranslationAPIFacade::getInstance()->__('Recently registered', 'pop-application'),
                'registered|ASC' => TranslationAPIFacade::getInstance()->__('Early registered', 'pop-application')
            )
        );

        return $values;
    }
}
