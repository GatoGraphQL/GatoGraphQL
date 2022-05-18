<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade;

class PoP_Module_Processor_Windows extends PoP_Module_Processor_WindowBase
{
    public final const COMPONENT_WINDOW_ADDONS = 'window-addons';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_WINDOW_ADDONS],
        );
    }

    public function getSubcomponents(array $component): array
    {
        return array_merge(
            parent::getSubcomponents($component),
            $this->getInnerSubmodules($component)
        );
    }

    protected function getInnerSubmodules(array $component): array
    {
        $pop_component_componentroutingprocessor_manager = ComponentRoutingProcessorManagerFacade::getInstance();

        switch ($component[1]) {
            case self::COMPONENT_WINDOW_ADDONS:
                $load_component = true;
                if (PoPThemeWassup_Utils::checkLoadingPagesectionModule()) {
                    $load_component = $component == $pop_component_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties(POP_PAGECOMPONENTGROUP_TOPLEVEL_CONTENTPAGESECTION);
                }

                $addons_component = [PoP_Module_Processor_TabPanes::class, PoP_Module_Processor_TabPanes::COMPONENT_PAGESECTION_ADDONS];
                $addontabs_component = [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::COMPONENT_PAGESECTION_ADDONTABS];
                if ($load_component) {
                    return array(
                        $addons_component,
                        $addontabs_component,
                    );
                }

                // Tell the pageSections to have no pages inside
                $componentAtts = array('empty' => true);
                return array(
                    [
                        $addons_component[0],
                        $addons_component[1],
                        $componentAtts
                    ],
                    [
                        $addontabs_component[0],
                        $addontabs_component[1],
                        $componentAtts
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
            case self::COMPONENT_WINDOW_ADDONS:
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
            case self::COMPONENT_WINDOW_ADDONS:
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


