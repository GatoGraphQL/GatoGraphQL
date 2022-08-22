<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

class PoP_Module_Processor_LocationSelectableTypeaheadFormInputs extends PoP_Module_Processor_LocationSelectableTypeaheadFormComponentsBase
{
    public final const COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATIONS = 'formcomponent-selectabletypeahead-locations';
    public final const COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATION = 'formcomponent-selectabletypeahead-location';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATIONS,
            self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATION,
        );
    }

    public function getComponentSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATIONS:
            case self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATION:
                return array(
                    [PoP_Module_Processor_LocationTypeaheadComponentFormInputs::class, PoP_Module_Processor_LocationTypeaheadComponentFormInputs::COMPONENT_TYPEAHEAD_COMPONENT_LOCATIONS],
                );
        }

        return parent::getComponentSubcomponents($component);
    }
    public function getTriggerLayoutSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATIONS:
                return [PoP_Module_Processor_LocationSelectableTypeaheadTriggerFormComponents::class, PoP_Module_Processor_LocationSelectableTypeaheadTriggerFormComponents::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_LOCATIONS];

            case self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATION:
                return [PoP_Module_Processor_LocationSelectableTypeaheadTriggerFormComponents::class, PoP_Module_Processor_LocationSelectableTypeaheadTriggerFormComponents::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_LOCATION];
        }

        return parent::getTriggerLayoutSubcomponent($component);
    }

    public function isMultiple(\PoP\ComponentModel\Component\Component $component): bool
    {
        switch ($component->name) {
            case self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATION:
                return false;
        }

        return parent::isMultiple($component);
    }

    public function getDbobjectField(\PoP\ComponentModel\Component\Component $component): ?string
    {
        switch ($component->name) {
            case self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATIONS:
                return 'locations';

            case self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATION:
                return 'location';
        }

        return parent::getDbobjectField($component);
    }

    protected function enableSuggestions(\PoP\ComponentModel\Component\Component $component)
    {

        // If there are suggestions, then enable the functionality
        return !empty($this->getSuggestions($component));
    }

    protected function getSuggestions(\PoP\ComponentModel\Component\Component $component)
    {
        return \PoP\Root\App::applyFilters(
            'PoP_Module_Processor_LocationSelectableTypeaheadFormInputs:suggestions',
            array(),
            $component
        );
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        switch ($component->name) {
            case self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATIONS:
            case self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATION:
                // Provide a pre-define list of locations
                if ($suggestions = $this->getSuggestions($component)) {
                    $this->setProp($component, $props, 'suggestions', $suggestions);
                }
                break;
        }

        parent::initModelProps($component, $props);
    }
}



