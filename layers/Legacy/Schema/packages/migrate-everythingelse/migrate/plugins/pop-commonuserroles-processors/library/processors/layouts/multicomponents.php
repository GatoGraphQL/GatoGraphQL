<?php

class GD_URE_Module_Processor_LayoutMultipleComponents extends PoP_Module_Processor_MultiplesBase
{
    public final const COMPONENT_MULTICOMPONENT_ORGANIZATIONDETAILS = 'multicomponent-organizationdetails';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_MULTICOMPONENT_ORGANIZATIONDETAILS],
        );
    }

    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_MULTICOMPONENT_ORGANIZATIONDETAILS:
                $ret[] = [GD_URE_Module_Processor_CategoriesLayouts::class, GD_URE_Module_Processor_CategoriesLayouts::COMPONENT_LAYOUT_ORGANIZATIONTYPES];
                $ret[] = [GD_URE_Module_Processor_CategoriesLayouts::class, GD_URE_Module_Processor_CategoriesLayouts::COMPONENT_LAYOUT_ORGANIZATIONCATEGORIES];
                break;
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_MULTICOMPONENT_ORGANIZATIONDETAILS:
                $components = $this->getSubcomponents($component);
                foreach ($components as $subComponent) {
                    $this->appendProp([$subComponent], $props, 'class', 'inline');
                }
                break;
        }

        parent::initModelProps($component, $props);
    }
}



