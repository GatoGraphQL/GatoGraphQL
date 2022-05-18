<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_URE_Module_Processor_TypeaheadTextFormInputs extends PoP_Module_Processor_TextFormInputsBase
{
    public final const MODULE_FORMINPUT_TEXT_TYPEAHEADORGANIZATIONS = 'forminput-text-typeaheadorganizations';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINPUT_TEXT_TYPEAHEADORGANIZATIONS],
        );
    }

    public function getLabelText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_TEXT_TYPEAHEADORGANIZATIONS:
                return TranslationAPIFacade::getInstance()->__('Organization', 'ure-popprocessors');
        }
        
        return parent::getLabelText($component, $props);
    }
}



