<?php

use PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade;

class PoP_Module_Processor_Modals extends PoP_Module_Processor_ModalsBase
{
    public final const MODULE_MODAL_QUICKVIEW = 'modal-quickview';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MODAL_QUICKVIEW],
        );
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        switch ($component[1]) {
            case self::MODULE_MODAL_QUICKVIEW:
                $this->addJsmethod($ret, 'customQuickView');
                $this->addJsmethod($ret, 'destroyPageOnModalClose', 'close');
                break;
        }

        return $ret;
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        $pop_component_componentroutingprocessor_manager = ComponentRoutingProcessorManagerFacade::getInstance();

        switch ($component[1]) {
            case self::MODULE_MODAL_QUICKVIEW:
                $load_component = true;
                if (PoPThemeWassup_Utils::checkLoadingPagesectionModule()) {
                    $load_component = $component == $pop_component_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties(POP_PAGEMODULEGROUP_TOPLEVEL_CONTENTPAGESECTION);
                }

                $quickview_component = [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::MODULE_PAGESECTION_QUICKVIEW];
                $quickviewsideinfo_component = [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::MODULE_PAGESECTION_QUICKVIEWSIDEINFO];
                if ($load_component) {
                    $ret[] = $quickview_component;
                    $ret[] = $quickviewsideinfo_component;
                } else {
                    // Tell the pageSections to have no pages inside
                    $moduleAtts = array('empty' => true);
                    $ret[] = [
                        $quickview_component[0],
                        $quickview_component[1], 
                        $moduleAtts
                    ];
                    $ret[] = [
                        $quickviewsideinfo_component[0],
                        $quickviewsideinfo_component[1], 
                        $moduleAtts
                    ];
                }
                break;
        }

        return $ret;
    }

    public function getBodyClass(array $component, array &$props)
    {
        $ret = parent::getBodyClass($component, $props);

        switch ($component[1]) {
            case self::MODULE_MODAL_QUICKVIEW:
                $ret .= ' pop-pagesection-group quickviewpagesection-group row';
                break;
        }

        return $ret;
    }
}


