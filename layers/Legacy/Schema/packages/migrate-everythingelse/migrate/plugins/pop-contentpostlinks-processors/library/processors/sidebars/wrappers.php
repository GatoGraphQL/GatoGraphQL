<?php

class PoP_ContentPostLinks_Module_Processor_WidgetWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public final const MODULE_LAYOUTWRAPPER_LINK_CATEGORIES = 'layoutwrapper-link-categories';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUTWRAPPER_LINK_CATEGORIES],
        );
    }

    public function getConditionSucceededSubmodules(array $componentVariation)
    {
        $ret = parent::getConditionSucceededSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUTWRAPPER_LINK_CATEGORIES:
                $ret[] = [PoP_ContentPostLinks_Module_Processor_CategoriesLayouts::class, PoP_ContentPostLinks_Module_Processor_CategoriesLayouts::MODULE_LAYOUT_LINK_CATEGORIES];
                break;
        }

        return $ret;
    }

    public function getConditionField(array $componentVariation): ?string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUTWRAPPER_LINK_CATEGORIES:
                return 'hasLinkCategories';
        }

        return null;
    }
}



