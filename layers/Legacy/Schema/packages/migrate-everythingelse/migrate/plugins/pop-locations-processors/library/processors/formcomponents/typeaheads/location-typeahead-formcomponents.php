<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

class PoP_Module_Processor_LocationSelectableTypeaheadFormInputs extends PoP_Module_Processor_LocationSelectableTypeaheadFormComponentsBase
{
    public final const MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATIONS = 'formcomponent-selectabletypeahead-locations';
    public final const MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATION = 'formcomponent-selectabletypeahead-location';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATIONS],
            [self::class, self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATION],
        );
    }

    public function getComponentSubmodules(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATIONS:
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATION:
                return array(
                    [PoP_Module_Processor_LocationTypeaheadComponentFormInputs::class, PoP_Module_Processor_LocationTypeaheadComponentFormInputs::MODULE_TYPEAHEAD_COMPONENT_LOCATIONS],
                );
        }

        return parent::getComponentSubmodules($componentVariation);
    }
    public function getTriggerLayoutSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATIONS:
                return [PoP_Module_Processor_LocationSelectableTypeaheadTriggerFormComponents::class, PoP_Module_Processor_LocationSelectableTypeaheadTriggerFormComponents::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_LOCATIONS];

            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATION:
                return [PoP_Module_Processor_LocationSelectableTypeaheadTriggerFormComponents::class, PoP_Module_Processor_LocationSelectableTypeaheadTriggerFormComponents::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_LOCATION];
        }

        return parent::getTriggerLayoutSubmodule($componentVariation);
    }

    public function isMultiple(array $componentVariation): bool
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATION:
                return false;
        }

        return parent::isMultiple($componentVariation);
    }

    public function getDbobjectField(array $componentVariation): ?string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATIONS:
                return 'locations';

            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATION:
                return 'location';
        }

        return parent::getDbobjectField($componentVariation);
    }

    protected function enableSuggestions(array $componentVariation)
    {

        // If there are suggestions, then enable the functionality
        return !empty($this->getSuggestions($componentVariation));
    }

    protected function getSuggestions(array $componentVariation)
    {
        return \PoP\Root\App::applyFilters(
            'PoP_Module_Processor_LocationSelectableTypeaheadFormInputs:suggestions',
            array(),
            $componentVariation
        );
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        switch ($componentVariation[1]) {
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATIONS:
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATION:
                // Provide a pre-define list of locations
                if ($suggestions = $this->getSuggestions($componentVariation)) {
                    $this->setProp($componentVariation, $props, 'suggestions', $suggestions);
                }
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



