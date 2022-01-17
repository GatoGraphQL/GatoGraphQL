<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoPCMSSchema\SchemaCommons\Facades\CMS\CMSServiceFacade;
use PoP\LooseContracts\Facades\NameResolverFacade;

abstract class GD_EM_Module_Processor_ScrollMapBlocksBase extends PoP_Module_Processor_SectionBlocksBase
{
    public function initModelProps(array $module, array &$props): void
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        $this->appendProp($module, $props, 'class', 'pop-block-map');
        $this->appendProp($module, $props, 'class', 'pop-block-scrollmap');

        $inner = $this->getInnerSubmodule($module);

        // Maps: bring twice the data (eg: normally 12, bring 24)
        $cmsService = CMSServiceFacade::getInstance();
        $limit = $cmsService->getOption(NameResolverFacade::getInstance()->getName('popcms:option:limit')) * 2;
        $this->setProp([$inner], $props, 'limit', $limit);

        // Make the map collapsible? Needed for the Homepage's Projects Widget, to collapse the map
        if ($this->getProp($module, $props, 'collapsible')) {
            $this->appendProp($module, $props, 'class', 'collapsible');

            // Add class "collapse" to the map, and properties to execute the cookies JS to open/close it as last done by the user
            $scrollmap = $moduleprocessor_manager->getProcessor($inner)->getInnerSubmodule($inner);
            $map = $moduleprocessor_manager->getProcessor($scrollmap)->getMapSubmodule($scrollmap);
            $this->appendProp([$inner, $scrollmap, $map], $props, 'class', 'collapse');
        }

        parent::initModelProps($module, $props);
    }
}
