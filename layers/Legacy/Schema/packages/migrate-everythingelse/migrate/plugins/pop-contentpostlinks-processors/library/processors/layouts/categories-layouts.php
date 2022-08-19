<?php

class PoP_ContentPostLinks_Module_Processor_CategoriesLayouts extends PoP_Module_Processor_CategoriesLayoutsBase
{
    public final const COMPONENT_LAYOUT_LINK_CATEGORIES = 'layout-link-categories';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_LINK_CATEGORIES,
        );
    }

    public function getCategoriesField(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_LINK_CATEGORIES:
                return 'linkCategoriesByName';
        }
        
        return parent::getCategoriesField($component, $props);
    }
}



