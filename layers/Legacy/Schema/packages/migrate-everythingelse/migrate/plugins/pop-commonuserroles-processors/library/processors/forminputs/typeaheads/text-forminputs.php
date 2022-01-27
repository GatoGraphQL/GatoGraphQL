<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_URE_Module_Processor_TypeaheadTextFormInputs extends PoP_Module_Processor_TextFormInputsBase
{
    public const MODULE_FORMINPUT_TEXT_TYPEAHEADORGANIZATIONS = 'forminput-text-typeaheadorganizations';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_TEXT_TYPEAHEADORGANIZATIONS],
        );
    }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_TEXT_TYPEAHEADORGANIZATIONS:
                return TranslationAPIFacade::getInstance()->__('Organization', 'ure-popprocessors');
        }
        
        return parent::getLabelText($module, $props);
    }
}



