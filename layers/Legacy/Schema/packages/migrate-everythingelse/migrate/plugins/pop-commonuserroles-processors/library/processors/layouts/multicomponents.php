<?php

class GD_URE_Module_Processor_LayoutMultipleComponents extends PoP_Module_Processor_MultiplesBase
{
    public final const MODULE_MULTICOMPONENT_ORGANIZATIONDETAILS = 'multicomponent-organizationdetails';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_MULTICOMPONENT_ORGANIZATIONDETAILS],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        switch ($component[1]) {
            case self::COMPONENT_MULTICOMPONENT_ORGANIZATIONDETAILS:
                $ret[] = [GD_URE_Module_Processor_CategoriesLayouts::class, GD_URE_Module_Processor_CategoriesLayouts::COMPONENT_LAYOUT_ORGANIZATIONTYPES];
                $ret[] = [GD_URE_Module_Processor_CategoriesLayouts::class, GD_URE_Module_Processor_CategoriesLayouts::COMPONENT_LAYOUT_ORGANIZATIONCATEGORIES];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_MULTICOMPONENT_ORGANIZATIONDETAILS:
                $components = $this->getSubComponents($component);
                foreach ($components as $subComponent) {
                    $this->appendProp([$subComponent], $props, 'class', 'inline');
                }
                break;
        }

        parent::initModelProps($component, $props);
    }
}



