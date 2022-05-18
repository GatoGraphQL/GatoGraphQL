<?php

class GD_URE_Module_Processor_CustomContentInners extends PoP_Module_Processor_ContentSingleInnersBase
{
    public final const MODULE_URE_CONTENTINNER_MEMBER = 'ure-contentinner-member';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_CONTENTINNER_MEMBER],
        );
    }

    public function getLayoutSubmodules(array $componentVariation)
    {
        $ret = parent::getLayoutSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_URE_CONTENTINNER_MEMBER:
                $ret[] = [PoP_Module_Processor_CustomPreviewUserLayouts::class, PoP_Module_Processor_CustomPreviewUserLayouts::MODULE_LAYOUT_PREVIEWUSER_HEADER];
                break;
        }

        return $ret;
    }
}


