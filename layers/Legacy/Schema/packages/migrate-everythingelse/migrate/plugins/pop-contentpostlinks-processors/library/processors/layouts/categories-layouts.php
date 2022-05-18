<?php

class PoP_ContentPostLinks_Module_Processor_CategoriesLayouts extends PoP_Module_Processor_CategoriesLayoutsBase
{
    public final const MODULE_LAYOUT_LINK_CATEGORIES = 'layout-link-categories';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_LINK_CATEGORIES],
        );
    }

    public function getCategoriesField(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_LINK_CATEGORIES:
                return 'linkCategoriesByName';
        }
        
        return parent::getCategoriesField($component, $props);
    }
}



