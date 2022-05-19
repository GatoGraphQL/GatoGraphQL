<?php

use PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade;

class PoP_Module_Processor_PageSectionContainers extends PoP_Module_Processor_MultiplesBase
{
    public final const COMPONENT_PAGESECTIONCONTAINER_HOLE = 'pagesectioncontainer-hole';
    public final const COMPONENT_PAGESECTIONCONTAINER_MODALS = 'pagesectioncontainer-modals';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_PAGESECTIONCONTAINER_HOLE],
            [self::class, self::COMPONENT_PAGESECTIONCONTAINER_MODALS],
        );
    }

    public function getSubcomponents(array $component): array
    {
        $ret = parent::getSubcomponents($component);

        $pop_component_componentroutingprocessor_manager = ComponentRoutingProcessorManagerFacade::getInstance();

        switch ($component[1]) {
            case self::COMPONENT_PAGESECTIONCONTAINER_HOLE:
            case self::COMPONENT_PAGESECTIONCONTAINER_MODALS:
                $load_component = true;
                if (PoPThemeWassup_Utils::checkLoadingPagesectionModule()) {
                    $load_component = $component == $pop_component_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties(POP_PAGECOMPONENTGROUP_TOPLEVEL_CONTENTPAGESECTION);
                }

                $subComponents = array(
                    self::COMPONENT_PAGESECTIONCONTAINER_HOLE => [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::COMPONENT_PAGESECTION_HOLE],
                    self::COMPONENT_PAGESECTIONCONTAINER_MODALS => [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::COMPONENT_PAGESECTION_MODALS],
                );
                $subComponent = $subComponents[$component[1]];

                if ($load_component) {
                    $ret[] = $subComponent;
                } else {
                    // Tell the pageSections to have no pages inside
                    $componentAtts = array('empty' => true);
                    $ret[] = [
                        $subComponent[0],
                        $subComponent[1],
                        $componentAtts
                    ];
                }
                break;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_PAGESECTIONCONTAINER_HOLE:
                $this->appendProp($component, $props, 'class', 'pagesection-group-after');
                break;
        }

        parent::initModelProps($component, $props);
    }
}


