<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoPCMSSchema\SchemaCommons\Facades\CMS\CMSServiceFacade;
use PoP\LooseContracts\Facades\NameResolverFacade;

abstract class GD_EM_Module_Processor_ScrollMapBlocksBase extends PoP_Module_Processor_SectionBlocksBase
{
    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $this->appendProp($component, $props, 'class', 'pop-block-map');
        $this->appendProp($component, $props, 'class', 'pop-block-scrollmap');

        $inner = $this->getInnerSubcomponent($component);

        // Maps: bring twice the data (eg: normally 12, bring 24)
        $cmsService = CMSServiceFacade::getInstance();
        $limit = $cmsService->getOption(NameResolverFacade::getInstance()->getName('popcms:option:limit')) * 2;
        $this->setProp([$inner], $props, 'limit', $limit);

        // Make the map collapsible? Needed for the Homepage's Projects Widget, to collapse the map
        if ($this->getProp($component, $props, 'collapsible')) {
            $this->appendProp($component, $props, 'class', 'collapsible');

            // Add class "collapse" to the map, and properties to execute the cookies JS to open/close it as last done by the user
            $scrollmap = $componentprocessor_manager->getProcessor($inner)->getInnerSubcomponent($inner);
            $map = $componentprocessor_manager->getProcessor($scrollmap)->getMapSubcomponent($scrollmap);
            $this->appendProp([$inner, $scrollmap, $map], $props, 'class', 'collapse');
        }

        parent::initModelProps($component, $props);
    }
}
