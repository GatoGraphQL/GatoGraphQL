<?php

abstract class PoP_Module_Processor_TabPanelSectionBlocksBase extends PoP_Module_Processor_SectionBlocksBase
{
    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        if ($filter_component = $this->getDelegatorfilterSubmodule($component)) {
            $ret[] = $filter_component;
        }

        return $ret;
    }

    protected function getDelegatorfilterSubmodule(array $component)
    {
        return null;
    }
}
