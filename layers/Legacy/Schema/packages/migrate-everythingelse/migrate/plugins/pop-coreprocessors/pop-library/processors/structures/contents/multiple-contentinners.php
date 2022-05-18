<?php

class PoPCore_Module_Processor_MultipleContentInners extends PoP_Module_Processor_ContentMultipleInnersBase
{
    public final const COMPONENT_CONTENTINNER_LATESTCOUNTS = 'contentinner-latestcounts';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_CONTENTINNER_LATESTCOUNTS],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_CONTENTINNER_LATESTCOUNTS:
                $ret[] = [PoP_Module_Processor_LatestCountScriptsLayouts::class, PoP_Module_Processor_LatestCountScriptsLayouts::COMPONENT_LAYOUT_LATESTCOUNTSCRIPT];
                break;
        }

        return $ret;
    }
}


