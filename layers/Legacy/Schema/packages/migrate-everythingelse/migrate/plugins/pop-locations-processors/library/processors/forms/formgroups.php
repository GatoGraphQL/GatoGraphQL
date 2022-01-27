<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_EM_Module_Processor_FormComponentGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public const MODULE_EM_FORMCOMPONENTGROUP_TYPEAHEADMAP = 'formcomponentgroup-locationsmap';
    public const MODULE_EM_FORMCOMPONENTGROUP_SINGLELOCATIONTYPEAHEADMAP = 'formcomponentgroup-singlelocationlocationsmap';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_EM_FORMCOMPONENTGROUP_TYPEAHEADMAP],
            [self::class, self::MODULE_EM_FORMCOMPONENTGROUP_SINGLELOCATIONTYPEAHEADMAP],
        );
    }

    public function getComponentSubmodule(array $module)
    {
        $components = array(
            self::MODULE_EM_FORMCOMPONENTGROUP_TYPEAHEADMAP => [PoP_Module_Processor_SelectableTypeaheadMapFormComponents::class, PoP_Module_Processor_SelectableTypeaheadMapFormComponents::MODULE_EM_FORMCOMPONENT_TYPEAHEADMAP],
            self::MODULE_EM_FORMCOMPONENTGROUP_SINGLELOCATIONTYPEAHEADMAP => [PoP_Module_Processor_SelectableTypeaheadMapFormComponents::class, PoP_Module_Processor_SelectableTypeaheadMapFormComponents::MODULE_EM_FORMCOMPONENT_SINGLELOCATIONTYPEAHEADMAP],
        );

        if ($component = $components[$module[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($module);
    }

    public function getInfo(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_EM_FORMCOMPONENTGROUP_TYPEAHEADMAP:
            case self::MODULE_EM_FORMCOMPONENTGROUP_SINGLELOCATIONTYPEAHEADMAP:
                return TranslationAPIFacade::getInstance()->__('If you can\'t find the location in the input below, click on the "+" button to add a new one.', 'em-popprocessors');
        }

        return parent::getInfo($module, $props);
    }

    public function initModelProps(array $module, array &$props): void
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        switch ($module[1]) {
            case self::MODULE_EM_FORMCOMPONENTGROUP_TYPEAHEADMAP:
            case self::MODULE_EM_FORMCOMPONENTGROUP_SINGLELOCATIONTYPEAHEADMAP:
                // Make it mandatory?
                if (\PoP\Root\App::applyFilters(
                    'GD_EM_Module_Processor_FormGroups:locations:mandatory',
                    false,
                    $module,
                    $props
                )
                ) {
                    $component = $this->getComponentSubmodule($module);
                    $this->setProp($component, $props, 'mandatory', true);
                }
                break;
        }

        parent::initModelProps($module, $props);
    }
}



