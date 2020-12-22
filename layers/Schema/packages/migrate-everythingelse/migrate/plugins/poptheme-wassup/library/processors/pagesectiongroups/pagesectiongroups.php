<?php

use PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade;

class PoP_Module_Processor_Entries extends PoP_Module_Processor_MultiplesBase
{
    public const MODULE_ENTRY_DEFAULT = 'entry-default';
    public const MODULE_ENTRY_PRINT = 'entry-print';
    public const MODULE_ENTRY_EMBED = 'entry-embed';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_ENTRY_DEFAULT],
            [self::class, self::MODULE_ENTRY_PRINT],
            [self::class, self::MODULE_ENTRY_EMBED],
        );
    }

    public function getSubmodules(array $module): array
    {
        switch ($module[1]) {
            case self::MODULE_ENTRY_DEFAULT:
                return array(
                    [PoP_Module_Processor_Offcanvas::class, PoP_Module_Processor_Offcanvas::MODULE_OFFCANVAS_TOP],
                    [PoP_Module_Processor_Offcanvas::class, PoP_Module_Processor_Offcanvas::MODULE_OFFCANVAS_SIDE],
                    [PoP_Module_Processor_Offcanvas::class, PoP_Module_Processor_Offcanvas::MODULE_OFFCANVAS_BACKGROUND],
                    [PoP_Module_Processor_Offcanvas::class, PoP_Module_Processor_Offcanvas::MODULE_OFFCANVAS_HOVER],
                    [PoP_Module_Processor_Offcanvas::class, PoP_Module_Processor_Offcanvas::MODULE_OFFCANVAS_NAVIGATOR],
                    [PoP_Module_Processor_PageSectionContainers::class, PoP_Module_Processor_PageSectionContainers::MODULE_PAGESECTIONCONTAINER_HOLE],
                    [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::MODULE_PAGESECTION_FRAMECOMPONENTS],
                    [PoP_Module_Processor_Offcanvas::class, PoP_Module_Processor_Offcanvas::MODULE_OFFCANVAS_BODY],
                    [PoP_Module_Processor_Offcanvas::class, PoP_Module_Processor_Offcanvas::MODULE_OFFCANVAS_BODYTABS],
                    [PoP_Module_Processor_Offcanvas::class, PoP_Module_Processor_Offcanvas::MODULE_OFFCANVAS_BODYSIDEINFO],
                    [PoP_Module_Processor_Windows::class, PoP_Module_Processor_Windows::MODULE_WINDOW_ADDONS],
                    [PoP_Module_Processor_PageSectionContainers::class, PoP_Module_Processor_PageSectionContainers::MODULE_PAGESECTIONCONTAINER_MODALS],
                    [PoP_Module_Processor_Modals::class, PoP_Module_Processor_Modals::MODULE_MODAL_QUICKVIEW],
                );
            
            case self::MODULE_ENTRY_PRINT:
                // Load all 3 pageSections (even if only 1 will show content) to guarantee that all pages are printable,
                // since all pages by default will open on 1 of these 3 (eg: https://getpop.org/en/log-in/?thememode=print opens in HOVER)
                // Adding target="addons" will not work any longer, however there is no link to print/embed anything with a target
                return array(
                    [PoP_Module_Processor_Offcanvas::class, PoP_Module_Processor_Offcanvas::MODULE_OFFCANVAS_HOVER],
                    [PoP_Module_Processor_PageSectionContainers::class, PoP_Module_Processor_PageSectionContainers::MODULE_PAGESECTIONCONTAINER_HOLE],
                    [PoP_Module_Processor_Offcanvas::class, PoP_Module_Processor_Offcanvas::MODULE_OFFCANVAS_BODY],
                );
            
            case self::MODULE_ENTRY_EMBED:
                return array(
                    [PoP_Module_Processor_Offcanvas::class, PoP_Module_Processor_Offcanvas::MODULE_OFFCANVAS_TOP],
                    [PoP_Module_Processor_Offcanvas::class, PoP_Module_Processor_Offcanvas::MODULE_OFFCANVAS_HOVER],
                    [PoP_Module_Processor_PageSectionContainers::class, PoP_Module_Processor_PageSectionContainers::MODULE_PAGESECTIONCONTAINER_HOLE],
                    [PoP_Module_Processor_Offcanvas::class, PoP_Module_Processor_Offcanvas::MODULE_OFFCANVAS_BODY],
                );
        }

        return parent::getSubmodules($module);
    }

    public function initModelProps(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_ENTRY_DEFAULT:
            case self::MODULE_ENTRY_PRINT:
            case self::MODULE_ENTRY_EMBED:
                $this->appendProp($module, $props, 'class', 'pop-pagesection-group pagesection-group');

                $active_pagesections = array(
                    self::MODULE_ENTRY_DEFAULT => array('active-top', 'active-side'),
                    self::MODULE_ENTRY_EMBED => array('active-top'),
                );
                if ($active_pagesections[$module[1]]) {
                    $this->appendProp($module, $props, 'class', implode(' ', PoPThemeWassup_Utils::getPagesectiongroupActivePagesectionClasses($active_pagesections[$module[1]])));
                }

                // When loading the whole site, only the main pageSection can have components retrieve params from the $_REQUEST
                // This way, passing &limit=4 doesn't affect the results on the widgets
                $pop_module_routemoduleprocessor_manager = RouteModuleProcessorManagerFacade::getInstance();
                $submodules = array_diff(
                    $this->getSubmodules($module),
                    [
                        $pop_module_routemoduleprocessor_manager->getRouteModuleByMostAllmatchingVarsProperties(POP_PAGEMODULEGROUP_TOPLEVEL_CONTENTPAGESECTION)
                    ]
                );
                foreach ($submodules as $submodule) {
                    $this->setProp($submodule, $props, 'ignore-request-params', true);
                }
                break;
        }
    
        parent::initModelProps($module, $props);
    }
}


