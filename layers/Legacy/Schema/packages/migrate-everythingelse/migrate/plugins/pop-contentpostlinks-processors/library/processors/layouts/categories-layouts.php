<?php

class PoP_ContentPostLinks_Module_Processor_CategoriesLayouts extends PoP_Module_Processor_CategoriesLayoutsBase
{
    public final const MODULE_LAYOUT_LINK_CATEGORIES = 'layout-link-categories';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_LINK_CATEGORIES],
        );
    }

    public function getCategoriesField(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_LINK_CATEGORIES:
                return 'linkCategoriesByName';
        }
        
        return parent::getCategoriesField($componentVariation, $props);
    }
}



