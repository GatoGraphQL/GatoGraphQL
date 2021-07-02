<?php
use PoP\Translation\Facades\TranslationAPIFacade;

class GD_UserCommunities_Module_Processor_TypeaheadTextFormInputs extends PoP_Module_Processor_TextFormInputsBase
{
    public const MODULE_FORMINPUT_TEXT_TYPEAHEADCOMMUNITIES = 'forminput-text-typeaheadcommunities';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_TEXT_TYPEAHEADCOMMUNITIES],
        );
    }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_TEXT_TYPEAHEADCOMMUNITIES:
                return TranslationAPIFacade::getInstance()->__('Community', 'ure-popprocessors');
        }
        
        return parent::getLabelText($module, $props);
    }
}



