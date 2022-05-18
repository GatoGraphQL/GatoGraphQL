<?php
use PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade;
use PoP\SPA\Modules\PageInterface;

class PoP_Module_Processor_PageTabs extends PoP_Module_Processor_PageTabPageSectionsBase implements PageInterface
{
    public final const MODULE_PAGE_ADDONTABS = 'page-addontabs';
    public final const MODULE_PAGE_BODYTABS = 'page-bodytabs';
    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_PAGE_ADDONTABS],
            [self::class, self::MODULE_PAGE_BODYTABS],
        );
    }

    public function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        $pop_module_routemoduleprocessor_manager = ComponentRoutingProcessorManagerFacade::getInstance();

        switch ($module[1]) {
            case self::MODULE_PAGE_ADDONTABS:
            case self::MODULE_PAGE_BODYTABS:
                if ($tab_module = $pop_module_routemoduleprocessor_manager->getRouteModuleByMostAllmatchingVarsProperties(POP_PAGEMODULEGROUP_PAGESECTION_TAB)) {
                    $ret[] = $tab_module;
                }
                break;
        }

        return $ret;
    }

    public function getBtnClass(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_PAGE_ADDONTABS:
                return 'btn btn-warning btn-sm';

            case self::MODULE_PAGE_BODYTABS:
                return 'btn btn-inverse btn-sm';
        }

        return parent::getBtnClass($module, $props);
    }
}



