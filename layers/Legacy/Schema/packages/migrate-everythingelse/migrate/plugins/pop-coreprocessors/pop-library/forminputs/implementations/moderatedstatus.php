<?php
use PoP\Engine\FormInputs\MultipleSelectFormInput;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\CustomPosts\Types\Status;

class GD_FormInput_ModeratedStatus extends MultipleSelectFormInput
{
    public function getAllValues($label = null): array
    {
        $values = parent::getAllValues($label);

        $values = array_merge(
            $values,
            array(
                Status::DRAFT => TranslationAPIFacade::getInstance()->__('Draft', 'pop-coreprocessors'),
                Status::PENDING => TranslationAPIFacade::getInstance()->__('Pending to be published', 'pop-coreprocessors'),
                Status::PUBLISHED => TranslationAPIFacade::getInstance()->__('Published', 'pop-coreprocessors')
            )
        );

        return $values;
    }
}
