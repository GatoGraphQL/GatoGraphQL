<?php

use PoP\ComponentRouting\Facades\RouteModuleProcessorManagerFacade;

class PoP_Module_Processor_PageSectionContainers extends PoP_Module_Processor_MultiplesBase
{
    public final const MODULE_PAGESECTIONCONTAINER_HOLE = 'pagesectioncontainer-hole';
    public final const MODULE_PAGESECTIONCONTAINER_MODALS = 'pagesectioncontainer-modals';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_PAGESECTIONCONTAINER_HOLE],
            [self::class, self::MODULE_PAGESECTIONCONTAINER_MODALS],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        $pop_module_routemoduleprocessor_manager = RouteModuleProcessorManagerFacade::getInstance();

        switch ($module[1]) {
            case self::MODULE_PAGESECTIONCONTAINER_HOLE:
            case self::MODULE_PAGESECTIONCONTAINER_MODALS:
                $load_module = true;
                if (PoPThemeWassup_Utils::checkLoadingPagesectionModule()) {
                    $load_module = $module == $pop_module_routemoduleprocessor_manager->getRouteModuleByMostAllmatchingVarsProperties(POP_PAGEMODULEGROUP_TOPLEVEL_CONTENTPAGESECTION);
                }

                $submodules = array(
                    self::MODULE_PAGESECTIONCONTAINER_HOLE => [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::MODULE_PAGESECTION_HOLE],
                    self::MODULE_PAGESECTIONCONTAINER_MODALS => [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::MODULE_PAGESECTION_MODALS],
                );
                $submodule = $submodules[$module[1]];

                if ($load_module) {
                    $ret[] = $submodule;
                } else {
                    // Tell the pageSections to have no pages inside
                    $moduleAtts = array('empty' => true);
                    $ret[] = [
                        $submodule[0],
                        $submodule[1],
                        $moduleAtts
                    ];
                }
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_PAGESECTIONCONTAINER_HOLE:
                $this->appendProp($module, $props, 'class', 'pagesection-group-after');
                break;
        }

        parent::initModelProps($module, $props);
    }
}


