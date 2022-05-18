<?php

abstract class PoP_Module_Processor_TabPanelSectionBlocksBase extends PoP_Module_Processor_SectionBlocksBase
{
    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        if ($filter_module = $this->getDelegatorfilterSubmodule($componentVariation)) {
            $ret[] = $filter_module;
        }

        return $ret;
    }

    protected function getDelegatorfilterSubmodule(array $componentVariation)
    {
        return null;
    }
}
