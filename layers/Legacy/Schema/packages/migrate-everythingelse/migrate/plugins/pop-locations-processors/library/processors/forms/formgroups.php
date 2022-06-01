<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_EM_Module_Processor_FormComponentGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const COMPONENT_EM_FORMCOMPONENTGROUP_TYPEAHEADMAP = 'formcomponentgroup-locationsmap';
    public final const COMPONENT_EM_FORMCOMPONENTGROUP_SINGLELOCATIONTYPEAHEADMAP = 'formcomponentgroup-singlelocationlocationsmap';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_EM_FORMCOMPONENTGROUP_TYPEAHEADMAP,
            self::COMPONENT_EM_FORMCOMPONENTGROUP_SINGLELOCATIONTYPEAHEADMAP,
        );
    }

    public function getComponentSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $components = array(
            self::COMPONENT_EM_FORMCOMPONENTGROUP_TYPEAHEADMAP => [PoP_Module_Processor_SelectableTypeaheadMapFormComponents::class, PoP_Module_Processor_SelectableTypeaheadMapFormComponents::COMPONENT_EM_FORMCOMPONENT_TYPEAHEADMAP],
            self::COMPONENT_EM_FORMCOMPONENTGROUP_SINGLELOCATIONTYPEAHEADMAP => [PoP_Module_Processor_SelectableTypeaheadMapFormComponents::class, PoP_Module_Processor_SelectableTypeaheadMapFormComponents::COMPONENT_EM_FORMCOMPONENT_SINGLELOCATIONTYPEAHEADMAP],
        );

        if ($component = $components[$component->name] ?? null) {
            return $component;
        }

        return parent::getComponentSubcomponent($component);
    }

    public function getInfo(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_EM_FORMCOMPONENTGROUP_TYPEAHEADMAP:
            case self::COMPONENT_EM_FORMCOMPONENTGROUP_SINGLELOCATIONTYPEAHEADMAP:
                return TranslationAPIFacade::getInstance()->__('If you can\'t find the location in the input below, click on the "+" button to add a new one.', 'em-popprocessors');
        }

        return parent::getInfo($component, $props);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        switch ($component->name) {
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
                    $component = $this->getComponentSubcomponent($component);
                    $this->setProp($component, $props, 'mandatory', true);
                }
                break;
        }

        parent::initModelProps($component, $props);
    }
}



