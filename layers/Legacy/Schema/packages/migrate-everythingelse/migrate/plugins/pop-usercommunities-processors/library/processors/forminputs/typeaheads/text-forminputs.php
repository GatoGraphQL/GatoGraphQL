<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_UserCommunities_Module_Processor_TypeaheadTextFormInputs extends PoP_Module_Processor_TextFormInputsBase
{
    public final const COMPONENT_FORMINPUT_TEXT_TYPEAHEADCOMMUNITIES = 'forminput-text-typeaheadcommunities';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMINPUT_TEXT_TYPEAHEADCOMMUNITIES,
        );
    }

    public function getLabelText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_TEXT_TYPEAHEADCOMMUNITIES:
                return TranslationAPIFacade::getInstance()->__('Community', 'ure-popprocessors');
        }
        
        return parent::getLabelText($component, $props);
    }
}



