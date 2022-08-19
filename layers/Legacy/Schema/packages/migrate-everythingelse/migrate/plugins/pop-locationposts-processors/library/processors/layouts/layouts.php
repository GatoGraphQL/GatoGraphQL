<?php

class GD_Custom_EM_Module_Processor_Layouts extends PoP_Module_Processor_CategoriesLayoutsBase
{
    public final const COMPONENT_LAYOUT_LOCATIONPOST_CATEGORIES = 'layout-locationpost-categories';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_LOCATIONPOST_CATEGORIES,
        );
    }

    public function getCategoriesField(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_LOCATIONPOST_CATEGORIES:
                return 'locationpostcategories-byname';
        }
        
        return parent::getCategoriesField($component, $props);
    }
}



