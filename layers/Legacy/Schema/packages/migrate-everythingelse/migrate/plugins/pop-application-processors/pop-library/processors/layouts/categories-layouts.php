<?php

class Wassup_Module_Processor_CategoriesLayouts extends PoP_Module_Processor_CategoriesLayoutsBase
{
    public final const MODULE_LAYOUT_CATEGORIES = 'layout-categories';
    public final const MODULE_LAYOUT_APPLIESTO = 'layout-appliesto';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_CATEGORIES],
            [self::class, self::COMPONENT_LAYOUT_APPLIESTO],
        );
    }

    public function getCategoriesField(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_CATEGORIES:
                return 'topicsByName';

            case self::COMPONENT_LAYOUT_APPLIESTO:
                return 'appliestoByName';
        }
        
        return parent::getCategoriesField($component, $props);
    }
    public function getLabelClass(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_APPLIESTO:
                return 'label-primary';
        }
        
        return parent::getLabelClass($component, $props);
    }
}



