<?php

class PoPCore_Module_Processor_MultipleContentInners extends PoP_Module_Processor_ContentMultipleInnersBase
{
    public final const COMPONENT_CONTENTINNER_LATESTCOUNTS = 'contentinner-latestcounts';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_CONTENTINNER_LATESTCOUNTS,
        );
    }

    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_CONTENTINNER_LATESTCOUNTS:
                $ret[] = [PoP_Module_Processor_LatestCountScriptsLayouts::class, PoP_Module_Processor_LatestCountScriptsLayouts::COMPONENT_LAYOUT_LATESTCOUNTSCRIPT];
                break;
        }

        return $ret;
    }
}


