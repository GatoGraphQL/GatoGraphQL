<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade;

class PoP_Module_Processor_Windows extends PoP_Module_Processor_WindowBase
{
    public final const MODULE_WINDOW_ADDONS = 'window-addons';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_WINDOW_ADDONS],
        );
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        return array_merge(
            parent::getSubComponentVariations($componentVariation),
            $this->getInnerSubmodules($componentVariation)
        );
    }

    protected function getInnerSubmodules(array $componentVariation): array
    {
        $pop_componentVariation_componentroutingprocessor_manager = ComponentRoutingProcessorManagerFacade::getInstance();

        switch ($componentVariation[1]) {
            case self::MODULE_WINDOW_ADDONS:
                $load_componentVariation = true;
                if (PoPThemeWassup_Utils::checkLoadingPagesectionModule()) {
                    $load_componentVariation = $componentVariation == $pop_componentVariation_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties(POP_PAGEMODULEGROUP_TOPLEVEL_CONTENTPAGESECTION);
                }

                $addons_componentVariation = [PoP_Module_Processor_TabPanes::class, PoP_Module_Processor_TabPanes::MODULE_PAGESECTION_ADDONS];
                $addontabs_componentVariation = [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::MODULE_PAGESECTION_ADDONTABS];
                if ($load_componentVariation) {
                    return array(
                        $addons_componentVariation,
                        $addontabs_componentVariation,
                    );
                }

                // Tell the pageSections to have no pages inside
                $moduleAtts = array('empty' => true);
                return array(
                    [
                        $addons_componentVariation[0],
                        $addons_componentVariation[1],
                        $moduleAtts
                    ],
                    [
                        $addontabs_componentVariation[0],
                        $addontabs_componentVariation[1],
                        $moduleAtts
                    ],
                );
        }

        return null;
    }

    protected function getModuleClasses(array $componentVariation, array &$props)
    {
        $ret = parent::getModuleClasses($componentVariation, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        switch ($componentVariation[1]) {
            case self::MODULE_WINDOW_ADDONS:
                list($addons_submodule, $addontabs_submodule) = $this->getInnerSubmodules($componentVariation);
                $addonsSubmoduleOutputName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($addons_submodule);
                $addontabsSubmoduleOutputName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($addontabs_submodule);
                $ret[$addonsSubmoduleOutputName] = 'container-fluid offcanvas pop-waypoints-context scrollable addons perfect-scrollbar vertical';
                $ret[$addontabsSubmoduleOutputName] = 'offcanvas pop-waypoints-context scrollable addontabs perfect-scrollbar horizontal navbar navbar-main navbar-addons';
                break;
        }

        return $ret;
    }

    protected function getModuleParams(array $componentVariation, array &$props)
    {
        $ret = parent::getModuleParams($componentVariation, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        switch ($componentVariation[1]) {
            case self::MODULE_WINDOW_ADDONS:
                list($addons_submodule, $addontabs_submodule) = $this->getInnerSubmodules($componentVariation);
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


