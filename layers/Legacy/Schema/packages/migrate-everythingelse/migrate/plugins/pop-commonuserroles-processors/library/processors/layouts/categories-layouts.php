<?php

class GD_URE_Module_Processor_CategoriesLayouts extends PoP_Module_Processor_CategoriesLayoutsBase
{
    public final const COMPONENT_LAYOUT_ORGANIZATIONCATEGORIES = 'layout-organizationcategories';
    public final const COMPONENT_LAYOUT_ORGANIZATIONTYPES = 'layout-organizationtypes';
    public final const COMPONENT_LAYOUT_INDIVIDUALINTERESTS = 'layout-individualinterests';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_ORGANIZATIONCATEGORIES,
            self::COMPONENT_LAYOUT_ORGANIZATIONTYPES,
            self::COMPONENT_LAYOUT_INDIVIDUALINTERESTS,
        );
    }

    public function getCategoriesField(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_ORGANIZATIONCATEGORIES:
                return 'organizationCategoriesByName';

            case self::COMPONENT_LAYOUT_ORGANIZATIONTYPES:
                return 'organizationTypesByName';

            case self::COMPONENT_LAYOUT_INDIVIDUALINTERESTS:
                return 'individualInterestsByName';
        }
        
        return parent::getCategoriesField($component, $props);
    }
    public function getLabelClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_ORGANIZATIONTYPES:
                return 'label-primary';
        }
        
        return parent::getLabelClass($component, $props);
    }
}



