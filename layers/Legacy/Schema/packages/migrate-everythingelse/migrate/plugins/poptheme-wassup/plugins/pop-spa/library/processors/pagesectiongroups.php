<?php

use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade;
use PoP\SPA\ModuleFilters\Page;

class PoP_SPA_Module_Processor_Entries extends PoP_Module_Processor_Entries
{
    public function getSubmodules(array $module): array
    {
        // If fetching a page, then load only the required pageSection modules and nothing else
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var Page */
        $page = $instanceManager->getInstance(Page::class);
        if (\PoP\Root\App::getState('modulefilter') == $page->getName()) {
            $ret = array();

            switch ($module[1]) {
                case self::MODULE_ENTRY_DEFAULT:
                    $pop_module_componentroutingprocessor_manager = ComponentRoutingProcessorManagerFacade::getInstance();
                    if ($content_pagesection_module = $pop_module_componentroutingprocessor_manager->getRouteModuleByMostAllmatchingVarsProperties(POP_PAGEMODULEGROUP_TOPLEVEL_CONTENTPAGESECTION)) {
                        $ret[] = $content_pagesection_module;

                        // Body and Addons need tabs.
                        if ($content_pagesection_module == [PoP_Module_Processor_Offcanvas::class, PoP_Module_Processor_Offcanvas::MODULE_OFFCANVAS_BODY]/* ||
                         $content_pagesection_module == [PoP_Module_Processor_TabPanes::class, PoP_Module_Processor_TabPanes::MODULE_PAGESECTION_ADDONS]*/
                        ) {
                            if ($tabs_pagesection_module = $pop_module_componentroutingprocessor_manager->getRouteModuleByMostAllmatchingVarsProperties(POP_PAGEMODULEGROUP_TOPLEVEL_TABSPAGESECTION)) {
                                $ret[] = $tabs_pagesection_module;
                            }
                        }
                        // Body and Quickview need sideinfo
                        if ($content_pagesection_module == [PoP_Module_Processor_Offcanvas::class, PoP_Module_Processor_Offcanvas::MODULE_OFFCANVAS_BODY]/* ||
                        $content_pagesection_module == [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::MODULE_PAGESECTION_QUICKVIEW]*/
                        ) {
                            if ($sideinfo_pagesection_module = $pop_module_componentroutingprocessor_manager->getRouteModuleByMostAllmatchingVarsProperties(POP_PAGEMODULEGROUP_TOPLEVEL_SIDEINFOPAGESECTION)) {
                                $ret[] = $sideinfo_pagesection_module;
                            }
                        }
                    }
                    break;

                case self::MODULE_ENTRY_PRINT:
                case self::MODULE_ENTRY_EMBED:
                    $pop_module_componentroutingprocessor_manager = ComponentRoutingProcessorManagerFacade::getInstance();
                    if ($content_pagesection_module = $pop_module_componentroutingprocessor_manager->getRouteModuleByMostAllmatchingVarsProperties(POP_PAGEMODULEGROUP_TOPLEVEL_CONTENTPAGESECTION)) {
                        $ret[] = $content_pagesection_module;
                    }
                    break;
            }

            return $ret;
        }

        // If loading the site, then print all pageSection modules
        return parent::getSubmodules($module);
    }
}


