<?php

use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade;
use PoP\SPA\ComponentFilters\Page;

class PoP_SPA_Module_Processor_Entries extends PoP_Module_Processor_Entries
{
    public function getSubComponents(array $component): array
    {
        // If fetching a page, then load only the required pageSection modules and nothing else
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var Page */
        $page = $instanceManager->getInstance(Page::class);
        if (\PoP\Root\App::getState('modulefilter') == $page->getName()) {
            $ret = array();

            switch ($component[1]) {
                case self::COMPONENT_ENTRY_DEFAULT:
                    $pop_component_componentroutingprocessor_manager = ComponentRoutingProcessorManagerFacade::getInstance();
                    if ($content_pagesection_component = $pop_component_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties(POP_PAGEMODULEGROUP_TOPLEVEL_CONTENTPAGESECTION)) {
                        $ret[] = $content_pagesection_component;

                        // Body and Addons need tabs.
                        if ($content_pagesection_component == [PoP_Module_Processor_Offcanvas::class, PoP_Module_Processor_Offcanvas::COMPONENT_OFFCANVAS_BODY]/* ||
                         $content_pagesection_component == [PoP_Module_Processor_TabPanes::class, PoP_Module_Processor_TabPanes::COMPONENT_PAGESECTION_ADDONS]*/
                        ) {
                            if ($tabs_pagesection_component = $pop_component_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties(POP_PAGEMODULEGROUP_TOPLEVEL_TABSPAGESECTION)) {
                                $ret[] = $tabs_pagesection_component;
                            }
                        }
                        // Body and Quickview need sideinfo
                        if ($content_pagesection_component == [PoP_Module_Processor_Offcanvas::class, PoP_Module_Processor_Offcanvas::COMPONENT_OFFCANVAS_BODY]/* ||
                        $content_pagesection_component == [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::COMPONENT_PAGESECTION_QUICKVIEW]*/
                        ) {
                            if ($sideinfo_pagesection_component = $pop_component_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties(POP_PAGEMODULEGROUP_TOPLEVEL_SIDEINFOPAGESECTION)) {
                                $ret[] = $sideinfo_pagesection_component;
                            }
                        }
                    }
                    break;

                case self::COMPONENT_ENTRY_PRINT:
                case self::COMPONENT_ENTRY_EMBED:
                    $pop_component_componentroutingprocessor_manager = ComponentRoutingProcessorManagerFacade::getInstance();
                    if ($content_pagesection_component = $pop_component_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties(POP_PAGEMODULEGROUP_TOPLEVEL_CONTENTPAGESECTION)) {
                        $ret[] = $content_pagesection_component;
                    }
                    break;
            }

            return $ret;
        }

        // If loading the site, then print all pageSection modules
        return parent::getSubComponents($component);
    }
}


