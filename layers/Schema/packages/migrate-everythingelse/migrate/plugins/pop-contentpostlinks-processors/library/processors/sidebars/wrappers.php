<?php

class PoP_ContentPostLinks_Module_Processor_WidgetWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public const MODULE_LAYOUTWRAPPER_LINK_CATEGORIES = 'layoutwrapper-link-categories';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUTWRAPPER_LINK_CATEGORIES],
        );
    }

    public function getConditionSucceededSubmodules(array $module)
    {
        $ret = parent::getConditionSucceededSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_LAYOUTWRAPPER_LINK_CATEGORIES:
                $ret[] = [PoP_ContentPostLinks_Module_Processor_CategoriesLayouts::class, PoP_ContentPostLinks_Module_Processor_CategoriesLayouts::MODULE_LAYOUT_LINK_CATEGORIES];
                break;
        }

        return $ret;
    }

    public function getConditionField(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUTWRAPPER_LINK_CATEGORIES:
                return 'hasLinkCategories';
        }

        return null;
    }
}



