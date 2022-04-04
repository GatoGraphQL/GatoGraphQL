<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_CAP_Module_Processor_TypeaheadTextFormInputs extends PoP_Module_Processor_TextFormInputsBase
{
    public final const MODULE_FORMINPUT_TEXT_TYPEAHEADPOSTAUTHORS = 'forminput-text-typeaheadpostauthors';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_TEXT_TYPEAHEADPOSTAUTHORS],
        );
    }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_TEXT_TYPEAHEADPOSTAUTHORS:
                return TranslationAPIFacade::getInstance()->__('Author(s)', 'pop-coreprocessors');
        }
        
        return parent::getLabelText($module, $props);
    }
}



