<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_LocationSelectableTypeaheadFormComponentsBase extends PoP_Module_Processor_PostSelectableTypeaheadFormComponentsBase
{
    use SuggestionsSelectableTypeaheadFormComponentsTrait;

    public function getInputSubmodule(array $componentVariation)
    {
        return [GD_EM_Module_Processor_InputGroupFormComponents::class, GD_EM_Module_Processor_InputGroupFormComponents::MODULE_FORMCOMPONENT_INPUTGROUP_TYPEAHEADADDLOCATION];
    }

    public function getSuggestionsLayoutSubmodule(array $componentVariation)
    {
        return [PoP_Module_Processor_LocationNameLayouts::class, PoP_Module_Processor_LocationNameLayouts::MODULE_EM_LAYOUT_LOCATIONNAME];
    }
    public function getSuggestionsFontawesome(array $componentVariation, array &$props)
    {
        return 'fa-fw fa-map-marker';
    }

    public function fixedId(array $componentVariation, array &$props): bool
    {
        return true;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        // Set the typeahead id on all the controls from the input module
        $input = $this->getInputSubmodule($componentVariation);
        $controls = $componentprocessor_manager->getProcessor($input)->getControlSubmodules($input);
        foreach ($controls as $control) {
            $this->mergeProp(
                $control,
                $props,
                'previousmodules-ids',
                array(
                    'data-typeahead-target' => $componentVariation,
                )
            );
        }

        parent::initModelProps($componentVariation, $props);
    }
}
