<?php

class GD_Custom_EM_Module_Processor_Layouts extends PoP_Module_Processor_CategoriesLayoutsBase
{
    public final const MODULE_LAYOUT_LOCATIONPOST_CATEGORIES = 'layout-locationpost-categories';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_LOCATIONPOST_CATEGORIES],
        );
    }

    public function getCategoriesField(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_LOCATIONPOST_CATEGORIES:
                return 'locationpostcategories-byname';
        }
        
        return parent::getCategoriesField($component, $props);
    }
}



