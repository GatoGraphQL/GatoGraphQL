<?php

use PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade;

class PoP_Module_Processor_Modals extends PoP_Module_Processor_ModalsBase
{
    public final const COMPONENT_MODAL_QUICKVIEW = 'modal-quickview';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_MODAL_QUICKVIEW,
        );
    }

    public function getJsmethods(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        switch ($component->name) {
            case self::COMPONENT_MODAL_QUICKVIEW:
                $this->addJsmethod($ret, 'customQuickView');
                $this->addJsmethod($ret, 'destroyPageOnModalClose', 'close');
                break;
        }

        return $ret;
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        $pop_component_componentroutingprocessor_manager = ComponentRoutingProcessorManagerFacade::getInstance();

        switch ($component->name) {
            case self::COMPONENT_MODAL_QUICKVIEW:
                $load_component = true;
                if (PoPThemeWassup_Utils::checkLoadingPagesectionModule()) {
                    $load_component = $component == $pop_component_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties(POP_PAGECOMPONENTGROUP_TOPLEVEL_CONTENTPAGESECTION);
                }

                $quickview_component = [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::COMPONENT_PAGESECTION_QUICKVIEW];
                $quickviewsideinfo_component = [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::COMPONENT_PAGESECTION_QUICKVIEWSIDEINFO];
                if ($load_component) {
                    $ret[] = $quickview_component;
                    $ret[] = $quickviewsideinfo_component;
                } else {
                    // Tell the pageSections to have no pages inside
                    $componentAtts = array('empty' => true);
                    $ret[] = [
                        $quickview_component[0],
                        $quickview_component[1], 
                        $componentAtts
                    ];
                    $ret[] = [
                        $quickviewsideinfo_component[0],
                        $quickviewsideinfo_component[1], 
                        $componentAtts
                    ];
                }
                break;
        }

        return $ret;
    }

    public function getBodyClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getBodyClass($component, $props);

        switch ($component->name) {
            case self::COMPONENT_MODAL_QUICKVIEW:
                $ret .= ' pop-pagesection-group quickviewpagesection-group row';
                break;
        }

        return $ret;
    }
}


