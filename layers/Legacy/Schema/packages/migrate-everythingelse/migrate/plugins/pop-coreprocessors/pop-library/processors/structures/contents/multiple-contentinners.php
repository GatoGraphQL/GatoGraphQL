<?php

class PoPCore_Module_Processor_MultipleContentInners extends PoP_Module_Processor_ContentMultipleInnersBase
{
    public final const COMPONENT_CONTENTINNER_LATESTCOUNTS = 'contentinner-latestcounts';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_CONTENTINNER_LATESTCOUNTS,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
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


