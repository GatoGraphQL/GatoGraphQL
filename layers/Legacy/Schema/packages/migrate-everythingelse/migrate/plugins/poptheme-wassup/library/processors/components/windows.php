<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\ComponentModel\Modules\ModuleUtils;
use PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade;

class PoP_Module_Processor_Windows extends PoP_Module_Processor_WindowBase
{
    public const MODULE_WINDOW_ADDONS = 'window-addons';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_WINDOW_ADDONS],
        );
    }

    public function getSubmodules(array $module): array
    {
        return array_merge(
            parent::getSubmodules($module),
            $this->getInnerSubmodules($module)
        );
    }

    protected function getInnerSubmodules(array $module): array
    {
        $pop_module_routemoduleprocessor_manager = RouteModuleProcessorManagerFacade::getInstance();

        switch ($module[1]) {
            case self::MODULE_WINDOW_ADDONS:
                $load_module = true;
                if (PoPThemeWassup_Utils::checkLoadingPagesectionModule()) {
                    $load_module = $module == $pop_module_routemoduleprocessor_manager->getRouteModuleByMostAllmatchingVarsProperties(POP_PAGEMODULEGROUP_TOPLEVEL_CONTENTPAGESECTION);
                }

                $addons_module = [PoP_Module_Processor_TabPanes::class, PoP_Module_Processor_TabPanes::MODULE_PAGESECTION_ADDONS];
                $addontabs_module = [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::MODULE_PAGESECTION_ADDONTABS];
                if ($load_module) {
                    return array(
                        $addons_module,
                        $addontabs_module,
                    );
                }

                // Tell the pageSections to have no pages inside
                $moduleAtts = array('empty' => true);
                return array(
                    [
                        $addons_module[0],
                        $addons_module[1],
                        $moduleAtts
                    ],
                    [
                        $addontabs_module[0],
                        $addontabs_module[1],
                        $moduleAtts
                    ],
                );
        }

        return null;
    }

    protected function getModuleClasses(array $module, array &$props)
    {
        $ret = parent::getModuleClasses($module, $props);

        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
        switch ($module[1]) {
            case self::MODULE_WINDOW_ADDONS:
                list($addons_submodule, $addontabs_submodule) = $this->getInnerSubmodules($module);
                $addonsSubmoduleOutputName = ModuleUtils::getModuleOutputName($addons_submodule);
                $addontabsSubmoduleOutputName = ModuleUtils::getModuleOutputName($addontabs_submodule);
                $ret[$addonsSubmoduleOutputName] = 'container-fluid offcanvas pop-waypoints-context scrollable addons perfect-scrollbar vertical';
                $ret[$addontabsSubmoduleOutputName] = 'offcanvas pop-waypoints-context scrollable addontabs perfect-scrollbar horizontal navbar navbar-main navbar-addons';
                break;
        }

        return $ret;
    }

    protected function getModuleParams(array $module, array &$props)
    {
        $ret = parent::getModuleParams($module, $props);

        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
        switch ($module[1]) {
            case self::MODULE_WINDOW_ADDONS:
                list($addons_submodule, $addontabs_submodule) = $this->getInnerSubmodules($module);
                $addonsSubmoduleOutputName = ModuleUtils::getModuleOutputName($addons_submodule);
                $addontabsSubmoduleOutputName = ModuleUtils::getModuleOutputName($addontabs_submodule);
                $ret[$addonsSubmoduleOutputName] = array(
                    'data-frametarget' => POP_TARGET_ADDONS,
                    'data-clickframetarget' => \PoP\ConfigurationComponentModel\Constants\Targets::MAIN,
                    'data-offcanvas' => 'addons',
                );
                $ret[$addontabsSubmoduleOutputName] = array(
                    'data-frametarget' => POP_TARGET_ADDONS,
                    'data-offcanvas' => 'addontabs',
                );
                break;
        }

        return $ret;
    }
}


