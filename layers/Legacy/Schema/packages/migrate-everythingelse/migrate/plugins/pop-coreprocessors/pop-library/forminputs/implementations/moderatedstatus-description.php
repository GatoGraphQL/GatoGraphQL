<?php
use PoP\Engine\FormInputs\SelectFormInput;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\CustomPosts\Types\Status;

class GD_FormInput_ModeratedStatusDescription extends SelectFormInput
{
    /**
     * @return mixed[]
     */
    public function getAllValues($label = null): array
    {
        $values = parent::getAllValues($label);

        $values = array_merge(
            $values,
            array(
                Status::DRAFT => TranslationAPIFacade::getInstance()->__('Draft (still editing)', 'pop-coreprocessors'),
                Status::PENDING => TranslationAPIFacade::getInstance()->__('Finished editing', 'pop-coreprocessors'),
                Status::PUBLISHED => TranslationAPIFacade::getInstance()->__('Already published', 'pop-coreprocessors')
            )
        );

        return $values;
    }

    public function getDefaultValue(): mixed
    {
        return Status::DRAFT;
    }
}
