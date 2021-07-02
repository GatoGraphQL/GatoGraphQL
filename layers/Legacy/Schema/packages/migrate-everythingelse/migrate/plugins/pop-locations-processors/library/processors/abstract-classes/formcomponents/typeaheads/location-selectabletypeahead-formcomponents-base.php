<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;

abstract class PoP_Module_Processor_LocationSelectableTypeaheadFormComponentsBase extends PoP_Module_Processor_PostSelectableTypeaheadFormComponentsBase
{
    use SuggestionsSelectableTypeaheadFormComponentsTrait;

    public function getInputSubmodule(array $module)
    {
        return [GD_EM_Module_Processor_InputGroupFormComponents::class, GD_EM_Module_Processor_InputGroupFormComponents::MODULE_FORMCOMPONENT_INPUTGROUP_TYPEAHEADADDLOCATION];
    }

    public function getSuggestionsLayoutSubmodule(array $module)
    {
        return [PoP_Module_Processor_LocationNameLayouts::class, PoP_Module_Processor_LocationNameLayouts::MODULE_EM_LAYOUT_LOCATIONNAME];
    }
    public function getSuggestionsFontawesome(array $module, array &$props)
    {
        return 'fa-fw fa-map-marker';
    }

    public function fixedId(array $module, array &$props): bool
    {
        return true;
    }

    public function initModelProps(array $module, array &$props): void
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        // Set the typeahead id on all the controls from the input module
        $input = $this->getInputSubmodule($module);
        $controls = $moduleprocessor_manager->getProcessor($input)->getControlSubmodules($input);
        foreach ($controls as $control) {
            $this->mergeProp(
                $control,
                $props,
                'previousmodules-ids',
                array(
                    'data-typeahead-target' => $module,
                )
            );
        }

        parent::initModelProps($module, $props);
    }
}
