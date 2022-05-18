<?php

class GD_EM_Module_Processor_MultipleUserLayouts extends PoP_Module_Processor_MultipleLayoutsBase
{
    public final const MODULE_LAYOUT_MULTIPLEUSER_MAPDETAILS = 'layout-multipleuser-mapdetails';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_MULTIPLEUSER_MAPDETAILS],
        );
    }

    public function getDefaultLayoutSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_MULTIPLEUSER_MAPDETAILS:
                return [GD_EM_Module_Processor_CustomPreviewUserLayouts::class, GD_EM_Module_Processor_CustomPreviewUserLayouts::MODULE_LAYOUT_PREVIEWUSER_MAPDETAILS];
        }

        return parent::getDefaultLayoutSubmodule($componentVariation);
    }

    public function getMultipleLayoutSubmodules(array $componentVariation)
    {
        $multilayout_manager = PoP_Application_MultilayoutManagerFactory::getInstance();
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_MULTIPLEUSER_MAPDETAILS:
                $handles = array(
                    self::MODULE_LAYOUT_MULTIPLEUSER_MAPDETAILS => POP_MULTILAYOUT_HANDLE_USERCONTENT,
                );
                return $multilayout_manager->getLayoutComponentVariations($handles[$componentVariation[1]], POP_FORMAT_MAP);
        }

        return parent::getMultipleLayoutSubmodules($componentVariation);
    }
}



