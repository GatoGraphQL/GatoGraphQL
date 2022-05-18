<?php

class PoP_Module_Processor_LocationContents extends PoP_Module_Processor_ContentsBase
{
    public final const MODULE_TRIGGERTYPEAHEADSELECT_LOCATION = 'triggertypeaheadselect-location';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_TRIGGERTYPEAHEADSELECT_LOCATION],
        );
    }

    public function getInnerSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_TRIGGERTYPEAHEADSELECT_LOCATION:
                return [PoP_Module_Processor_LocationContentInners::class, PoP_Module_Processor_LocationContentInners::MODULE_TRIGGERTYPEAHEADSELECTINNER_LOCATION];
        }

        return parent::getInnerSubmodule($componentVariation);
    }
}


