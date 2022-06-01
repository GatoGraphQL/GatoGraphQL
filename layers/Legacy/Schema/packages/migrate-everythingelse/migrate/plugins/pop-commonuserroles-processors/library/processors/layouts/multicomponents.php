<?php

class GD_URE_Module_Processor_LayoutMultipleComponents extends PoP_Module_Processor_MultiplesBase
{
    public final const COMPONENT_MULTICOMPONENT_ORGANIZATIONDETAILS = 'multicomponent-organizationdetails';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_MULTICOMPONENT_ORGANIZATIONDETAILS,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_MULTICOMPONENT_ORGANIZATIONDETAILS:
                $ret[] = [GD_URE_Module_Processor_CategoriesLayouts::class, GD_URE_Module_Processor_CategoriesLayouts::COMPONENT_LAYOUT_ORGANIZATIONTYPES];
                $ret[] = [GD_URE_Module_Processor_CategoriesLayouts::class, GD_URE_Module_Processor_CategoriesLayouts::COMPONENT_LAYOUT_ORGANIZATIONCATEGORIES];
                break;
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_MULTICOMPONENT_ORGANIZATIONDETAILS:
                $components = $this->getSubcomponents($component);
                foreach ($components as $subcomponent) {
                    $this->appendProp([$subcomponent], $props, 'class', 'inline');
                }
                break;
        }

        parent::initModelProps($component, $props);
    }
}



