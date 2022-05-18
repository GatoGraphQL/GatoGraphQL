<?php

class GD_URE_Module_Processor_CustomContents extends PoP_Module_Processor_ContentsBase
{
    public final const MODULE_URE_CONTENT_MEMBER = 'ure-content-member';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_CONTENT_MEMBER],
        );
    }

    public function getInnerSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_URE_CONTENT_MEMBER:
                return [GD_URE_Module_Processor_CustomContentInners::class, GD_URE_Module_Processor_CustomContentInners::MODULE_URE_CONTENTINNER_MEMBER];
        }

        return parent::getInnerSubmodule($componentVariation);
    }
}


