<?php
use PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade;
use PoP\SPA\ComponentProcessors\PageComponentProcessorInterface;

class PoP_Module_Processor_PageTabs extends PoP_Module_Processor_PageTabPageSectionsBase implements PageComponentProcessorInterface
{
    public final const COMPONENT_PAGE_ADDONTABS = 'page-addontabs';
    public final const COMPONENT_PAGE_BODYTABS = 'page-bodytabs';
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_PAGE_ADDONTABS,
            self::COMPONENT_PAGE_BODYTABS,
        );
    }

    public function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        $pop_component_componentroutingprocessor_manager = ComponentRoutingProcessorManagerFacade::getInstance();

        switch ($component->name) {
            case self::COMPONENT_PAGE_ADDONTABS:
            case self::COMPONENT_PAGE_BODYTABS:
                if ($tab_component = $pop_component_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties(POP_PAGECOMPONENTGROUP_PAGESECTION_TAB)) {
                    $ret[] = $tab_component;
                }
                break;
        }

        return $ret;
    }

    public function getBtnClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_PAGE_ADDONTABS:
                return 'btn btn-warning btn-sm';

            case self::COMPONENT_PAGE_BODYTABS:
                return 'btn btn-inverse btn-sm';
        }

        return parent::getBtnClass($component, $props);
    }
}



