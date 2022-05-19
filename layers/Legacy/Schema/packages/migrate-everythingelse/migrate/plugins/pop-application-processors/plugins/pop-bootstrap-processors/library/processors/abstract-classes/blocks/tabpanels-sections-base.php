<?php

abstract class PoP_Module_Processor_TabPanelSectionBlocksBase extends PoP_Module_Processor_SectionBlocksBase
{
    public function getSubcomponents(array $component): array
    {
        $ret = parent::getSubcomponents($component);

        if ($filter_component = $this->getDelegatorfilterSubcomponent($component)) {
            $ret[] = $filter_component;
        }

        return $ret;
    }

    protected function getDelegatorfilterSubcomponent(array $component)
    {
        return null;
    }
}
