<?php

abstract class PoP_Module_Processor_TabPanelSectionBlocksBase extends PoP_Module_Processor_SectionBlocksBase
{
    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        if ($filter_module = $this->getDelegatorfilterSubmodule($module)) {
            $ret[] = $filter_module;
        }

        return $ret;
    }

    protected function getDelegatorfilterSubmodule(array $module)
    {
        return null;
    }
}
