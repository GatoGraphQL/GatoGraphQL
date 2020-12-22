<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\CustomPosts\Types\Status;

class GD_FormInput_UnmoderatedStatus extends \PoP\Engine\GD_FormInput_MultiSelect
{
    public function getAllValues($label = null)
    {
        $values = parent::getAllValues($label);

        $values = array_merge(
            $values,
            array(
                Status::DRAFT => TranslationAPIFacade::getInstance()->__('Draft', 'pop-coreprocessors'),
                Status::PUBLISHED => TranslationAPIFacade::getInstance()->__('Published', 'pop-coreprocessors')
            )
        );

        return $values;
    }
}
