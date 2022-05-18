<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_SelectableTypeaheadMapFormComponents extends PoP_Module_Processor_SelectableTypeaheadMapFormComponentsBase
{
    public final const MODULE_EM_FORMCOMPONENT_TYPEAHEADMAP = 'formcomponent-locationsmap';
    public final const MODULE_EM_FORMCOMPONENT_SINGLELOCATIONTYPEAHEADMAP = 'formcomponent-singlelocationlocationsmap';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_EM_FORMCOMPONENT_TYPEAHEADMAP],
            [self::class, self::MODULE_EM_FORMCOMPONENT_SINGLELOCATIONTYPEAHEADMAP],
        );
    }

    public function getLocationsTypeaheadSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_EM_FORMCOMPONENT_TYPEAHEADMAP:
                return [PoP_Module_Processor_LocationSelectableTypeaheadFormInputs::class, PoP_Module_Processor_LocationSelectableTypeaheadFormInputs::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATIONS];

            case self::MODULE_EM_FORMCOMPONENT_SINGLELOCATIONTYPEAHEADMAP:
                return [PoP_Module_Processor_LocationSelectableTypeaheadFormInputs::class, PoP_Module_Processor_LocationSelectableTypeaheadFormInputs::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATION];
        }
    
        return parent::getLocationsTypeaheadSubmodule($componentVariation);
    }

    public function getLabelText(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_EM_FORMCOMPONENT_TYPEAHEADMAP:
                return TranslationAPIFacade::getInstance()->__('Location(s)', 'em-popprocessors');

            case self::MODULE_EM_FORMCOMPONENT_SINGLELOCATIONTYPEAHEADMAP:
                return TranslationAPIFacade::getInstance()->__('Location', 'em-popprocessors');
        }
        
        return parent::getLabelText($componentVariation, $props);
    }
}



