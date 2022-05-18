<?php

use PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade;

class PoP_Module_Processor_Modals extends PoP_Module_Processor_ModalsBase
{
    public final const MODULE_MODAL_QUICKVIEW = 'modal-quickview';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MODAL_QUICKVIEW],
        );
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_MODAL_QUICKVIEW:
                $this->addJsmethod($ret, 'customQuickView');
                $this->addJsmethod($ret, 'destroyPageOnModalClose', 'close');
                break;
        }

        return $ret;
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        $pop_componentVariation_componentroutingprocessor_manager = ComponentRoutingProcessorManagerFacade::getInstance();

        switch ($componentVariation[1]) {
            case self::MODULE_MODAL_QUICKVIEW:
                $load_componentVariation = true;
                if (PoPThemeWassup_Utils::checkLoadingPagesectionModule()) {
                    $load_componentVariation = $componentVariation == $pop_componentVariation_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties(POP_PAGEMODULEGROUP_TOPLEVEL_CONTENTPAGESECTION);
                }

                $quickview_componentVariation = [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::MODULE_PAGESECTION_QUICKVIEW];
                $quickviewsideinfo_componentVariation = [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::MODULE_PAGESECTION_QUICKVIEWSIDEINFO];
                if ($load_componentVariation) {
                    $ret[] = $quickview_componentVariation;
                    $ret[] = $quickviewsideinfo_componentVariation;
                } else {
                    // Tell the pageSections to have no pages inside
                    $moduleAtts = array('empty' => true);
                    $ret[] = [
                        $quickview_componentVariation[0],
                        $quickview_componentVariation[1], 
                        $moduleAtts
                    ];
                    $ret[] = [
                        $quickviewsideinfo_componentVariation[0],
                        $quickviewsideinfo_componentVariation[1], 
                        $moduleAtts
                    ];
                }
                break;
        }

        return $ret;
    }

    public function getBodyClass(array $componentVariation, array &$props)
    {
        $ret = parent::getBodyClass($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_MODAL_QUICKVIEW:
                $ret .= ' pop-pagesection-group quickviewpagesection-group row';
                break;
        }

        return $ret;
    }
}


