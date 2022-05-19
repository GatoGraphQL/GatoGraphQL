<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_SelectableTypeaheadMapFormComponents extends PoP_Module_Processor_SelectableTypeaheadMapFormComponentsBase
{
    public final const COMPONENT_EM_FORMCOMPONENT_TYPEAHEADMAP = 'formcomponent-locationsmap';
    public final const COMPONENT_EM_FORMCOMPONENT_SINGLELOCATIONTYPEAHEADMAP = 'formcomponent-singlelocationlocationsmap';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_EM_FORMCOMPONENT_TYPEAHEADMAP],
            [self::class, self::COMPONENT_EM_FORMCOMPONENT_SINGLELOCATIONTYPEAHEADMAP],
        );
    }

    public function getLocationsTypeaheadSubcomponent(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_EM_FORMCOMPONENT_TYPEAHEADMAP:
                return [PoP_Module_Processor_LocationSelectableTypeaheadFormInputs::class, PoP_Module_Processor_LocationSelectableTypeaheadFormInputs::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATIONS];

            case self::COMPONENT_EM_FORMCOMPONENT_SINGLELOCATIONTYPEAHEADMAP:
                return [PoP_Module_Processor_LocationSelectableTypeaheadFormInputs::class, PoP_Module_Processor_LocationSelectableTypeaheadFormInputs::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATION];
        }
    
        return parent::getLocationsTypeaheadSubcomponent($component);
    }

    public function getLabelText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_EM_FORMCOMPONENT_TYPEAHEADMAP:
                return TranslationAPIFacade::getInstance()->__('Location(s)', 'em-popprocessors');

            case self::COMPONENT_EM_FORMCOMPONENT_SINGLELOCATIONTYPEAHEADMAP:
                return TranslationAPIFacade::getInstance()->__('Location', 'em-popprocessors');
        }
        
        return parent::getLabelText($component, $props);
    }
}



