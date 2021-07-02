<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoPSchema\CustomPosts\Types\Status;

class PoP_Module_Processor_CreateUpdatePostCheckboxFormInputs extends PoP_Module_Processor_BooleanCheckboxFormInputsBase
{
    public const MODULE_FORMINPUT_CUP_KEEPASDRAFT = 'forminput-keepasdraft';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_CUP_KEEPASDRAFT],
        );
    }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_CUP_KEEPASDRAFT:
                return TranslationAPIFacade::getInstance()->__('Keep as draft?', 'poptheme-wassup');
        }

        return parent::getLabelText($module, $props);
    }

    public function getDbobjectField(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_CUP_KEEPASDRAFT:
                return FieldQueryInterpreterFacade::getInstance()->getField('isStatus', ['status' => Status::DRAFT], 'is-draft');
        }

        return parent::getDbobjectField($module);
    }
}



