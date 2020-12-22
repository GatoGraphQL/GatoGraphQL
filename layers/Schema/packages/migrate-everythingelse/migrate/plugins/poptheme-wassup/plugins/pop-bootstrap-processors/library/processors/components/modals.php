<?php

use PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade;

class PoP_Module_Processor_Modals extends PoP_Module_Processor_ModalsBase
{
    public const MODULE_MODAL_QUICKVIEW = 'modal-quickview';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MODAL_QUICKVIEW],
        );
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        switch ($module[1]) {
            case self::MODULE_MODAL_QUICKVIEW:
                $this->addJsmethod($ret, 'customQuickView');
                $this->addJsmethod($ret, 'destroyPageOnModalClose', 'close');
                break;
        }

        return $ret;
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        $pop_module_routemoduleprocessor_manager = RouteModuleProcessorManagerFacade::getInstance();

        switch ($module[1]) {
            case self::MODULE_MODAL_QUICKVIEW:
                $load_module = true;
                if (PoPThemeWassup_Utils::checkLoadingPagesectionModule()) {
                    $load_module = $module == $pop_module_routemoduleprocessor_manager->getRouteModuleByMostAllmatchingVarsProperties(POP_PAGEMODULEGROUP_TOPLEVEL_CONTENTPAGESECTION);
                }

                $quickview_module = [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::MODULE_PAGESECTION_QUICKVIEW];
                $quickviewsideinfo_module = [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::MODULE_PAGESECTION_QUICKVIEWSIDEINFO];
                if ($load_module) {
                    $ret[] = $quickview_module;
                    $ret[] = $quickviewsideinfo_module;
                } else {
                    // Tell the pageSections to have no pages inside
                    $moduleAtts = array('empty' => true);
                    $ret[] = [
                        $quickview_module[0],
                        $quickview_module[1], 
                        $moduleAtts
                    ];
                    $ret[] = [
                        $quickviewsideinfo_module[0],
                        $quickviewsideinfo_module[1], 
                        $moduleAtts
                    ];
                }
                break;
        }

        return $ret;
    }

    public function getBodyClass(array $module, array &$props)
    {
        $ret = parent::getBodyClass($module, $props);

        switch ($module[1]) {
            case self::MODULE_MODAL_QUICKVIEW:
                $ret .= ' pop-pagesection-group quickviewpagesection-group row';
                break;
        }

        return $ret;
    }
}


