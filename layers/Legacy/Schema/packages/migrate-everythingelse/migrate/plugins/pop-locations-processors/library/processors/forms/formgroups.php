<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_EM_Module_Processor_FormComponentGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const MODULE_EM_FORMCOMPONENTGROUP_TYPEAHEADMAP = 'formcomponentgroup-locationsmap';
    public final const MODULE_EM_FORMCOMPONENTGROUP_SINGLELOCATIONTYPEAHEADMAP = 'formcomponentgroup-singlelocationlocationsmap';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_EM_FORMCOMPONENTGROUP_TYPEAHEADMAP],
            [self::class, self::MODULE_EM_FORMCOMPONENTGROUP_SINGLELOCATIONTYPEAHEADMAP],
        );
    }

    public function getComponentSubmodule(array $componentVariation)
    {
        $components = array(
            self::MODULE_EM_FORMCOMPONENTGROUP_TYPEAHEADMAP => [PoP_Module_Processor_SelectableTypeaheadMapFormComponents::class, PoP_Module_Processor_SelectableTypeaheadMapFormComponents::MODULE_EM_FORMCOMPONENT_TYPEAHEADMAP],
            self::MODULE_EM_FORMCOMPONENTGROUP_SINGLELOCATIONTYPEAHEADMAP => [PoP_Module_Processor_SelectableTypeaheadMapFormComponents::class, PoP_Module_Processor_SelectableTypeaheadMapFormComponents::MODULE_EM_FORMCOMPONENT_SINGLELOCATIONTYPEAHEADMAP],
        );

        if ($component = $components[$componentVariation[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($componentVariation);
    }

    public function getInfo(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_EM_FORMCOMPONENTGROUP_TYPEAHEADMAP:
            case self::MODULE_EM_FORMCOMPONENTGROUP_SINGLELOCATIONTYPEAHEADMAP:
                return TranslationAPIFacade::getInstance()->__('If you can\'t find the location in the input below, click on the "+" button to add a new one.', 'em-popprocessors');
        }

        return parent::getInfo($componentVariation, $props);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        switch ($componentVariation[1]) {
            case self::MODULE_EM_FORMCOMPONENTGROUP_TYPEAHEADMAP:
            case self::MODULE_EM_FORMCOMPONENTGROUP_SINGLELOCATIONTYPEAHEADMAP:
                // Make it mandatory?
                if (\PoP\Root\App::applyFilters(
                    'GD_EM_Module_Processor_FormGroups:locations:mandatory',
                    false,
                    $componentVariation,
                    $props
                )
                ) {
                    $component = $this->getComponentSubmodule($componentVariation);
                    $this->setProp($component, $props, 'mandatory', true);
                }
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



