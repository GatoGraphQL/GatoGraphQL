<?php

class PoP_ContentPostLinks_Module_Processor_WidgetWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public final const MODULE_LAYOUTWRAPPER_LINK_CATEGORIES = 'layoutwrapper-link-categories';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUTWRAPPER_LINK_CATEGORIES],
        );
    }

    public function getConditionSucceededSubmodules(array $component)
    {
        $ret = parent::getConditionSucceededSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUTWRAPPER_LINK_CATEGORIES:
                $ret[] = [PoP_ContentPostLinks_Module_Processor_CategoriesLayouts::class, PoP_ContentPostLinks_Module_Processor_CategoriesLayouts::COMPONENT_LAYOUT_LINK_CATEGORIES];
                break;
        }

        return $ret;
    }

    public function getConditionField(array $component): ?string
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUTWRAPPER_LINK_CATEGORIES:
                return 'hasLinkCategories';
        }

        return null;
    }
}



