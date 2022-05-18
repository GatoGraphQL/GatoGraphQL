<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade;

class PoP_Module_Processor_Windows extends PoP_Module_Processor_WindowBase
{
    public final const MODULE_WINDOW_ADDONS = 'window-addons';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_WINDOW_ADDONS],
        );
    }

    public function getSubComponents(array $component): array
    {
        return array_merge(
            parent::getSubComponents($component),
            $this->getInnerSubmodules($component)
        );
    }

    protected function getInnerSubmodules(array $component): array
    {
        $pop_component_componentroutingprocessor_manager = ComponentRoutingProcessorManagerFacade::getInstance();

        switch ($component[1]) {
            case self::MODULE_WINDOW_ADDONS:
                $load_component = true;
                if (PoPThemeWassup_Utils::checkLoadingPagesectionModule()) {
                    $load_component = $component == $pop_component_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties(POP_PAGEMODULEGROUP_TOPLEVEL_CONTENTPAGESECTION);
                }

                $addons_component = [PoP_Module_Processor_TabPanes::class, PoP_Module_Processor_TabPanes::MODULE_PAGESECTION_ADDONS];
                $addontabs_component = [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::MODULE_PAGESECTION_ADDONTABS];
                if ($load_component) {
                    return array(
                        $addons_component,
                        $addontabs_component,
                    );
                }

                // Tell the pageSections to have no pages inside
                $moduleAtts = array('empty' => true);
                return array(
                    [
                        $addons_component[0],
                        $addons_component[1],
                        $moduleAtts
                    ],
                    [
                        $addontabs_component[0],
                        $addontabs_component[1],
                        $moduleAtts
                    ],
                );
        }

        return null;
    }

    protected function getModuleClasses(array $component, array &$props)
    {
        $ret = parent::getModuleClasses($component, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        switch ($component[1]) {
            case self::MODULE_WINDOW_ADDONS:
                list($addons_submodule, $addontabs_submodule) = $this->getInnerSubmodules($component);
                $addonsSubmoduleOutputName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($addons_submodule);
                $addontabsSubmoduleOutputName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($addontabs_submodule);
                $ret[$addonsSubmoduleOutputName] = 'container-fluid offcanvas pop-waypoints-context scrollable addons perfect-scrollbar vertical';
                $ret[$addontabsSubmoduleOutputName] = 'offcanvas pop-waypoints-context scrollable addontabs perfect-scrollbar horizontal navbar navbar-main navbar-addons';
                break;
        }

        return $ret;
    }

    protected function getModuleParams(array $component, array &$props)
    {
        $ret = parent::getModuleParams($component, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        switch ($component[1]) {
            case self::MODULE_WINDOW_ADDONS:
                list($addons_submodule, $addontabs_submodule) = $this->getInnerSubmodules($component);
                $addonsSubmoduleOutputName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($addons_submodule);
                $addontabsSubmoduleOutputName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($addontabs_submodule);
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


