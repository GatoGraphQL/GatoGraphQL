<?php

class GD_URE_Module_Processor_LayoutMultipleComponents extends PoP_Module_Processor_MultiplesBase
{
    public final const MODULE_MULTICOMPONENT_ORGANIZATIONDETAILS = 'multicomponent-organizationdetails';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MULTICOMPONENT_ORGANIZATIONDETAILS],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_MULTICOMPONENT_ORGANIZATIONDETAILS:
                $ret[] = [GD_URE_Module_Processor_CategoriesLayouts::class, GD_URE_Module_Processor_CategoriesLayouts::MODULE_LAYOUT_ORGANIZATIONTYPES];
                $ret[] = [GD_URE_Module_Processor_CategoriesLayouts::class, GD_URE_Module_Processor_CategoriesLayouts::MODULE_LAYOUT_ORGANIZATIONCATEGORIES];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_MULTICOMPONENT_ORGANIZATIONDETAILS:
                $modules = $this->getSubmodules($module);
                foreach ($modules as $submodule) {
                    $this->appendProp([$submodule], $props, 'class', 'inline');
                }
                break;
        }

        parent::initModelProps($module, $props);
    }
}



