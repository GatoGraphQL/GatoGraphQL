<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\CustomPosts\Types\Status;

class GD_FormInput_ModeratedStatusDescription extends \PoP\Engine\GD_FormInput_Select
{
    public function getAllValues($label = null)
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

    public function getDefaultValue()
    {
        return Status::DRAFT;
    }
}
