<?php

class GD_URE_Module_Processor_CategoriesLayouts extends PoP_Module_Processor_CategoriesLayoutsBase
{
    public final const MODULE_LAYOUT_ORGANIZATIONCATEGORIES = 'layout-organizationcategories';
    public final const MODULE_LAYOUT_ORGANIZATIONTYPES = 'layout-organizationtypes';
    public final const MODULE_LAYOUT_INDIVIDUALINTERESTS = 'layout-individualinterests';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_ORGANIZATIONCATEGORIES],
            [self::class, self::COMPONENT_LAYOUT_ORGANIZATIONTYPES],
            [self::class, self::COMPONENT_LAYOUT_INDIVIDUALINTERESTS],
        );
    }

    public function getCategoriesField(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_ORGANIZATIONCATEGORIES:
                return 'organizationCategoriesByName';

            case self::COMPONENT_LAYOUT_ORGANIZATIONTYPES:
                return 'organizationTypesByName';

            case self::COMPONENT_LAYOUT_INDIVIDUALINTERESTS:
                return 'individualInterestsByName';
        }
        
        return parent::getCategoriesField($component, $props);
    }
    public function getLabelClass(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_ORGANIZATIONTYPES:
                return 'label-primary';
        }
        
        return parent::getLabelClass($component, $props);
    }
}



