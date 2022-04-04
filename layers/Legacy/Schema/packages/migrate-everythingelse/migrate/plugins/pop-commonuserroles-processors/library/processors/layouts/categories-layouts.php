<?php

class GD_URE_Module_Processor_CategoriesLayouts extends PoP_Module_Processor_CategoriesLayoutsBase
{
    public final const MODULE_LAYOUT_ORGANIZATIONCATEGORIES = 'layout-organizationcategories';
    public final const MODULE_LAYOUT_ORGANIZATIONTYPES = 'layout-organizationtypes';
    public final const MODULE_LAYOUT_INDIVIDUALINTERESTS = 'layout-individualinterests';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_ORGANIZATIONCATEGORIES],
            [self::class, self::MODULE_LAYOUT_ORGANIZATIONTYPES],
            [self::class, self::MODULE_LAYOUT_INDIVIDUALINTERESTS],
        );
    }

    public function getCategoriesField(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_ORGANIZATIONCATEGORIES:
                return 'organizationCategoriesByName';

            case self::MODULE_LAYOUT_ORGANIZATIONTYPES:
                return 'organizationTypesByName';

            case self::MODULE_LAYOUT_INDIVIDUALINTERESTS:
                return 'individualInterestsByName';
        }
        
        return parent::getCategoriesField($module, $props);
    }
    public function getLabelClass(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_ORGANIZATIONTYPES:
                return 'label-primary';
        }
        
        return parent::getLabelClass($module, $props);
    }
}



