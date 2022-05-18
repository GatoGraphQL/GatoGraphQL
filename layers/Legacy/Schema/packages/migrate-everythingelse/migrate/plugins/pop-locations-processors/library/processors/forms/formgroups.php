<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_EM_Module_Processor_FormComponentGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const COMPONENT_EM_FORMCOMPONENTGROUP_TYPEAHEADMAP = 'formcomponentgroup-locationsmap';
    public final const COMPONENT_EM_FORMCOMPONENTGROUP_SINGLELOCATIONTYPEAHEADMAP = 'formcomponentgroup-singlelocationlocationsmap';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_EM_FORMCOMPONENTGROUP_TYPEAHEADMAP],
            [self::class, self::COMPONENT_EM_FORMCOMPONENTGROUP_SINGLELOCATIONTYPEAHEADMAP],
        );
    }

    public function getComponentSubmodule(array $component)
    {
        $components = array(
            self::COMPONENT_EM_FORMCOMPONENTGROUP_TYPEAHEADMAP => [PoP_Module_Processor_SelectableTypeaheadMapFormComponents::class, PoP_Module_Processor_SelectableTypeaheadMapFormComponents::COMPONENT_EM_FORMCOMPONENT_TYPEAHEADMAP],
            self::COMPONENT_EM_FORMCOMPONENTGROUP_SINGLELOCATIONTYPEAHEADMAP => [PoP_Module_Processor_SelectableTypeaheadMapFormComponents::class, PoP_Module_Processor_SelectableTypeaheadMapFormComponents::COMPONENT_EM_FORMCOMPONENT_SINGLELOCATIONTYPEAHEADMAP],
        );

        if ($component = $components[$component[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($component);
    }

    public function getInfo(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_EM_FORMCOMPONENTGROUP_TYPEAHEADMAP:
            case self::COMPONENT_EM_FORMCOMPONENTGROUP_SINGLELOCATIONTYPEAHEADMAP:
                return TranslationAPIFacade::getInstance()->__('If you can\'t find the location in the input below, click on the "+" button to add a new one.', 'em-popprocessors');
        }

        return parent::getInfo($component, $props);
    }

    public function initModelProps(array $component, array &$props): void
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        switch ($component[1]) {
            case self::COMPONENT_EM_FORMCOMPONENTGROUP_TYPEAHEADMAP:
            case self::COMPONENT_EM_FORMCOMPONENTGROUP_SINGLELOCATIONTYPEAHEADMAP:
                // Make it mandatory?
                if (\PoP\Root\App::applyFilters(
                    'GD_EM_Module_Processor_FormGroups:locations:mandatory',
                    false,
                    $component,
                    $props
                )
                ) {
                    $component = $this->getComponentSubmodule($component);
                    $this->setProp($component, $props, 'mandatory', true);
                }
                break;
        }

        parent::initModelProps($component, $props);
    }
}



