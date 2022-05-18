<?php

use PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade;

class PoP_Module_Processor_PageSectionContainers extends PoP_Module_Processor_MultiplesBase
{
    public final const MODULE_PAGESECTIONCONTAINER_HOLE = 'pagesectioncontainer-hole';
    public final const MODULE_PAGESECTIONCONTAINER_MODALS = 'pagesectioncontainer-modals';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_PAGESECTIONCONTAINER_HOLE],
            [self::class, self::MODULE_PAGESECTIONCONTAINER_MODALS],
        );
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        $pop_module_componentroutingprocessor_manager = ComponentRoutingProcessorManagerFacade::getInstance();

        switch ($componentVariation[1]) {
            case self::MODULE_PAGESECTIONCONTAINER_HOLE:
            case self::MODULE_PAGESECTIONCONTAINER_MODALS:
                $load_module = true;
                if (PoPThemeWassup_Utils::checkLoadingPagesectionModule()) {
                    $load_module = $componentVariation == $pop_module_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties(POP_PAGEMODULEGROUP_TOPLEVEL_CONTENTPAGESECTION);
                }

                $subComponentVariations = array(
                    self::MODULE_PAGESECTIONCONTAINER_HOLE => [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::MODULE_PAGESECTION_HOLE],
                    self::MODULE_PAGESECTIONCONTAINER_MODALS => [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::MODULE_PAGESECTION_MODALS],
                );
                $submodule = $subComponentVariations[$componentVariation[1]];

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

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_PAGESECTIONCONTAINER_HOLE:
                $this->appendProp($componentVariation, $props, 'class', 'pagesection-group-after');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}


