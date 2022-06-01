<?php

use PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade;

class PoP_Module_Processor_PageSectionContainers extends PoP_Module_Processor_MultiplesBase
{
    public final const COMPONENT_PAGESECTIONCONTAINER_HOLE = 'pagesectioncontainer-hole';
    public final const COMPONENT_PAGESECTIONCONTAINER_MODALS = 'pagesectioncontainer-modals';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_PAGESECTIONCONTAINER_HOLE,
            self::COMPONENT_PAGESECTIONCONTAINER_MODALS,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        $pop_component_componentroutingprocessor_manager = ComponentRoutingProcessorManagerFacade::getInstance();

        switch ($component->name) {
            case self::COMPONENT_PAGESECTIONCONTAINER_HOLE:
            case self::COMPONENT_PAGESECTIONCONTAINER_MODALS:
                $load_component = true;
                if (PoPThemeWassup_Utils::checkLoadingPagesectionModule()) {
                    $load_component = $component == $pop_component_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties(POP_PAGECOMPONENTGROUP_TOPLEVEL_CONTENTPAGESECTION);
                }

                $subcomponents = array(
                    self::COMPONENT_PAGESECTIONCONTAINER_HOLE => [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::COMPONENT_PAGESECTION_HOLE],
                    self::COMPONENT_PAGESECTIONCONTAINER_MODALS => [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::COMPONENT_PAGESECTION_MODALS],
                );
                $subcomponent = $subcomponents[$component->name];

                if ($load_component) {
                    $ret[] = $subcomponent;
                } else {
                    // Tell the pageSections to have no pages inside
                    $componentAtts = array('empty' => true);
                    $ret[] = [
                        $subcomponent[0],
                        $subcomponent[1],
                        $componentAtts
                    ];
                }
                break;
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_PAGESECTIONCONTAINER_HOLE:
                $this->appendProp($component, $props, 'class', 'pagesection-group-after');
                break;
        }

        parent::initModelProps($component, $props);
    }
}


