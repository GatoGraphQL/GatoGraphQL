<?php

use PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade;

class PoP_Module_Processor_Entries extends PoP_Module_Processor_MultiplesBase
{
    public final const MODULE_ENTRY_DEFAULT = 'entry-default';
    public final const MODULE_ENTRY_PRINT = 'entry-print';
    public final const MODULE_ENTRY_EMBED = 'entry-embed';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_ENTRY_DEFAULT],
            [self::class, self::MODULE_ENTRY_PRINT],
            [self::class, self::MODULE_ENTRY_EMBED],
        );
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        switch ($componentVariation[1]) {
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

        return parent::getSubComponentVariations($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_ENTRY_DEFAULT:
            case self::MODULE_ENTRY_PRINT:
            case self::MODULE_ENTRY_EMBED:
                $this->appendProp($componentVariation, $props, 'class', 'pop-pagesection-group pagesection-group');

                $active_pagesections = array(
                    self::MODULE_ENTRY_DEFAULT => array('active-top', 'active-side'),
                    self::MODULE_ENTRY_EMBED => array('active-top'),
                );
                if ($active_pagesections[$componentVariation[1]] ?? null) {
                    $this->appendProp($componentVariation, $props, 'class', implode(' ', PoPThemeWassup_Utils::getPagesectiongroupActivePagesectionClasses($active_pagesections[$componentVariation[1]] ?? null)));
                }

                // When loading the whole site, only the main pageSection can have components retrieve params from the $_GET
                // This way, passing &limit=4 doesn't affect the results on the widgets
                $pop_module_componentroutingprocessor_manager = ComponentRoutingProcessorManagerFacade::getInstance();
                $subComponentVariations = array_diff(
                    $this->getSubComponentVariations($componentVariation),
                    [
                        $pop_module_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties(POP_PAGEMODULEGROUP_TOPLEVEL_CONTENTPAGESECTION)
                    ]
                );
                foreach ($subComponentVariations as $submodule) {
                    $this->setProp($submodule, $props, 'ignore-request-params', true);
                }
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}


