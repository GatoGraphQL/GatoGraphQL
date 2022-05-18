<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_URE_Module_Processor_TypeaheadTextFormInputs extends PoP_Module_Processor_TextFormInputsBase
{
    public final const MODULE_FORMINPUT_TEXT_TYPEAHEADORGANIZATIONS = 'forminput-text-typeaheadorganizations';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_TEXT_TYPEAHEADORGANIZATIONS],
        );
    }

    public function getLabelText(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_TEXT_TYPEAHEADORGANIZATIONS:
                return TranslationAPIFacade::getInstance()->__('Organization', 'ure-popprocessors');
        }
        
        return parent::getLabelText($componentVariation, $props);
    }
}



