<?php

class GD_EM_Module_Processor_MultipleUserLayouts extends PoP_Module_Processor_MultipleLayoutsBase
{
    public final const COMPONENT_LAYOUT_MULTIPLEUSER_MAPDETAILS = 'layout-multipleuser-mapdetails';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_MULTIPLEUSER_MAPDETAILS],
        );
    }

    public function getDefaultLayoutSubcomponent(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_MULTIPLEUSER_MAPDETAILS:
                return [GD_EM_Module_Processor_CustomPreviewUserLayouts::class, GD_EM_Module_Processor_CustomPreviewUserLayouts::COMPONENT_LAYOUT_PREVIEWUSER_MAPDETAILS];
        }

        return parent::getDefaultLayoutSubcomponent($component);
    }

    public function getMultipleLayoutSubcomponents(array $component)
    {
        $multilayout_manager = PoP_Application_MultilayoutManagerFactory::getInstance();
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_MULTIPLEUSER_MAPDETAILS:
                $handles = array(
                    self::COMPONENT_LAYOUT_MULTIPLEUSER_MAPDETAILS => POP_MULTILAYOUT_HANDLE_USERCONTENT,
                );
                return $multilayout_manager->getLayoutComponents($handles[$component[1]], POP_FORMAT_MAP);
        }

        return parent::getMultipleLayoutSubcomponents($component);
    }
}



