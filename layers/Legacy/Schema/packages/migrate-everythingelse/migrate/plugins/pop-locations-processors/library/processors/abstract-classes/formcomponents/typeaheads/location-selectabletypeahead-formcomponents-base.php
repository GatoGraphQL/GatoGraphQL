<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_LocationSelectableTypeaheadFormComponentsBase extends PoP_Module_Processor_PostSelectableTypeaheadFormComponentsBase
{
    use SuggestionsSelectableTypeaheadFormComponentsTrait;

    public function getInputSubcomponent(array $component)
    {
        return [GD_EM_Module_Processor_InputGroupFormComponents::class, GD_EM_Module_Processor_InputGroupFormComponents::COMPONENT_FORMCOMPONENT_INPUTGROUP_TYPEAHEADADDLOCATION];
    }

    public function getSuggestionsLayoutSubcomponent(array $component)
    {
        return [PoP_Module_Processor_LocationNameLayouts::class, PoP_Module_Processor_LocationNameLayouts::COMPONENT_EM_LAYOUT_LOCATIONNAME];
    }
    public function getSuggestionsFontawesome(array $component, array &$props)
    {
        return 'fa-fw fa-map-marker';
    }

    public function fixedId(array $component, array &$props): bool
    {
        return true;
    }

    public function initModelProps(array $component, array &$props): void
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        // Set the typeahead id on all the controls from the input module
        $input = $this->getInputSubcomponent($component);
        $controls = $componentprocessor_manager->getProcessor($input)->getControlSubcomponents($input);
        foreach ($controls as $control) {
            $this->mergeProp(
                $control,
                $props,
                'previouscomponents-ids',
                array(
                    'data-typeahead-target' => $component,
                )
            );
        }

        parent::initModelProps($component, $props);
    }
}
