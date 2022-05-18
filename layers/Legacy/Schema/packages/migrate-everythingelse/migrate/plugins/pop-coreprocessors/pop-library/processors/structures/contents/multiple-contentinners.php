<?php

class PoPCore_Module_Processor_MultipleContentInners extends PoP_Module_Processor_ContentMultipleInnersBase
{
    public final const MODULE_CONTENTINNER_LATESTCOUNTS = 'contentinner-latestcounts';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CONTENTINNER_LATESTCOUNTS],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_CONTENTINNER_LATESTCOUNTS:
                $ret[] = [PoP_Module_Processor_LatestCountScriptsLayouts::class, PoP_Module_Processor_LatestCountScriptsLayouts::MODULE_LAYOUT_LATESTCOUNTSCRIPT];
                break;
        }

        return $ret;
    }
}


