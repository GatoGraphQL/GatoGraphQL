<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\CustomPosts\Types\Status;
use PoP\Engine\FormInputs\SelectFormInput;

class GD_FormInput_UnmoderatedStatusDescription extends SelectFormInput
{
    public function getAllValues($label = null)
    {
        $values = parent::getAllValues($label);

        $values = array_merge(
            $values,
            array(
                Status::DRAFT => TranslationAPIFacade::getInstance()->__('Draft (still editing)', 'pop-coreprocessors'),
                Status::PUBLISHED => TranslationAPIFacade::getInstance()->__('Already published', 'pop-coreprocessors')
            )
        );

        return $values;
    }

    public function getDefaultValue()
    {
        return Status::DRAFT;
    }
}
