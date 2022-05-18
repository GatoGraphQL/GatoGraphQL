<?php

class GD_Custom_EM_Module_Processor_Layouts extends PoP_Module_Processor_CategoriesLayoutsBase
{
    public final const MODULE_LAYOUT_LOCATIONPOST_CATEGORIES = 'layout-locationpost-categories';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_LOCATIONPOST_CATEGORIES],
        );
    }

    public function getCategoriesField(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_LOCATIONPOST_CATEGORIES:
                return 'locationpostcategories-byname';
        }
        
        return parent::getCategoriesField($module, $props);
    }
}



