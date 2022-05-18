<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\CustomPosts\Types\Status;

class PoP_Module_Processor_CreateUpdatePostCheckboxFormInputs extends PoP_Module_Processor_BooleanCheckboxFormInputsBase
{
    public final const MODULE_FORMINPUT_CUP_KEEPASDRAFT = 'forminput-keepasdraft';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINPUT_CUP_KEEPASDRAFT],
        );
    }

    public function getLabelText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_CUP_KEEPASDRAFT:
                return TranslationAPIFacade::getInstance()->__('Keep as draft?', 'poptheme-wassup');
        }

        return parent::getLabelText($component, $props);
    }

    public function getDbobjectField(array $component): ?string
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_CUP_KEEPASDRAFT:
                return FieldQueryInterpreterFacade::getInstance()->getField('isStatus', ['status' => Status::DRAFT], 'is-draft');
        }

        return parent::getDbobjectField($component);
    }
}



