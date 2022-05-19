<?php
use PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade;
use PoP\SPA\Modules\PageInterface;

class PoP_Module_Processor_PageTabs extends PoP_Module_Processor_PageTabPageSectionsBase implements PageInterface
{
    public final const COMPONENT_PAGE_ADDONTABS = 'page-addontabs';
    public final const COMPONENT_PAGE_BODYTABS = 'page-bodytabs';
    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_PAGE_ADDONTABS],
            [self::class, self::COMPONENT_PAGE_BODYTABS],
        );
    }

    public function getInnerSubcomponents(array $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        $pop_component_componentroutingprocessor_manager = ComponentRoutingProcessorManagerFacade::getInstance();

        switch ($component[1]) {
            case self::COMPONENT_PAGE_ADDONTABS:
            case self::COMPONENT_PAGE_BODYTABS:
                if ($tab_component = $pop_component_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties(POP_PAGECOMPONENTGROUP_PAGESECTION_TAB)) {
                    $ret[] = $tab_component;
                }
                break;
        }

        return $ret;
    }

    public function getBtnClass(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_PAGE_ADDONTABS:
                return 'btn btn-warning btn-sm';

            case self::COMPONENT_PAGE_BODYTABS:
                return 'btn btn-inverse btn-sm';
        }

        return parent::getBtnClass($component, $props);
    }
}



