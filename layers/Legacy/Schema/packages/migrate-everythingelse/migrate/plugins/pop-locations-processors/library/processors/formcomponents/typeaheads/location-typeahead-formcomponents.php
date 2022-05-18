<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

class PoP_Module_Processor_LocationSelectableTypeaheadFormInputs extends PoP_Module_Processor_LocationSelectableTypeaheadFormComponentsBase
{
    public final const MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATIONS = 'formcomponent-selectabletypeahead-locations';
    public final const MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATION = 'formcomponent-selectabletypeahead-location';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATIONS],
            [self::class, self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATION],
        );
    }

    public function getComponentSubmodules(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATIONS:
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATION:
                return array(
                    [PoP_Module_Processor_LocationTypeaheadComponentFormInputs::class, PoP_Module_Processor_LocationTypeaheadComponentFormInputs::MODULE_TYPEAHEAD_COMPONENT_LOCATIONS],
                );
        }

        return parent::getComponentSubmodules($component);
    }
    public function getTriggerLayoutSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATIONS:
                return [PoP_Module_Processor_LocationSelectableTypeaheadTriggerFormComponents::class, PoP_Module_Processor_LocationSelectableTypeaheadTriggerFormComponents::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_LOCATIONS];

            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATION:
                return [PoP_Module_Processor_LocationSelectableTypeaheadTriggerFormComponents::class, PoP_Module_Processor_LocationSelectableTypeaheadTriggerFormComponents::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_LOCATION];
        }

        return parent::getTriggerLayoutSubmodule($component);
    }

    public function isMultiple(array $component): bool
    {
        switch ($component[1]) {
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATION:
                return false;
        }

        return parent::isMultiple($component);
    }

    public function getDbobjectField(array $component): ?string
    {
        switch ($component[1]) {
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATIONS:
                return 'locations';

            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATION:
                return 'location';
        }

        return parent::getDbobjectField($component);
    }

    protected function enableSuggestions(array $component)
    {

        // If there are suggestions, then enable the functionality
        return !empty($this->getSuggestions($component));
    }

    protected function getSuggestions(array $component)
    {
        return \PoP\Root\App::applyFilters(
            'PoP_Module_Processor_LocationSelectableTypeaheadFormInputs:suggestions',
            array(),
            $component
        );
    }

    public function initModelProps(array $component, array &$props): void
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        switch ($component[1]) {
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATIONS:
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATION:
                // Provide a pre-define list of locations
                if ($suggestions = $this->getSuggestions($component)) {
                    $this->setProp($component, $props, 'suggestions', $suggestions);
                }
                break;
        }

        parent::initModelProps($component, $props);
    }
}



