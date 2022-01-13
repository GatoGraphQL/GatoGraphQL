<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;

class PoP_Module_Processor_LocationSelectableTypeaheadFormInputs extends PoP_Module_Processor_LocationSelectableTypeaheadFormComponentsBase
{
    public const MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATIONS = 'formcomponent-selectabletypeahead-locations';
    public const MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATION = 'formcomponent-selectabletypeahead-location';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATIONS],
            [self::class, self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATION],
        );
    }

    public function getComponentSubmodules(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATIONS:
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATION:
                return array(
                    [PoP_Module_Processor_LocationTypeaheadComponentFormInputs::class, PoP_Module_Processor_LocationTypeaheadComponentFormInputs::MODULE_TYPEAHEAD_COMPONENT_LOCATIONS],
                );
        }

        return parent::getComponentSubmodules($module);
    }
    public function getTriggerLayoutSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATIONS:
                return [PoP_Module_Processor_LocationSelectableTypeaheadTriggerFormComponents::class, PoP_Module_Processor_LocationSelectableTypeaheadTriggerFormComponents::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_LOCATIONS];

            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATION:
                return [PoP_Module_Processor_LocationSelectableTypeaheadTriggerFormComponents::class, PoP_Module_Processor_LocationSelectableTypeaheadTriggerFormComponents::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_LOCATION];
        }

        return parent::getTriggerLayoutSubmodule($module);
    }

    public function isMultiple(array $module): bool
    {
        switch ($module[1]) {
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATION:
                return false;
        }

        return parent::isMultiple($module);
    }

    public function getDbobjectField(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATIONS:
                return 'locations';

            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATION:
                return 'location';
        }

        return parent::getDbobjectField($module);
    }

    protected function enableSuggestions(array $module)
    {

        // If there are suggestions, then enable the functionality
        return !empty($this->getSuggestions($module));
    }

    protected function getSuggestions(array $module)
    {
        return \PoP\Root\App::getHookManager()->applyFilters(
            'PoP_Module_Processor_LocationSelectableTypeaheadFormInputs:suggestions',
            array(),
            $module
        );
    }

    public function initModelProps(array $module, array &$props): void
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        switch ($module[1]) {
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATIONS:
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATION:
                // Provide a pre-define list of locations
                if ($suggestions = $this->getSuggestions($module)) {
                    $this->setProp($module, $props, 'suggestions', $suggestions);
                }
                break;
        }

        parent::initModelProps($module, $props);
    }
}



