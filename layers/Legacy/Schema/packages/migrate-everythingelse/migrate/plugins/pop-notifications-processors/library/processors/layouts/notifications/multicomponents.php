<?php

class PoP_Module_Processor_MultipleComponentLayouts extends PoP_Module_Processor_MultiplesBase
{
    public const MODULE_AAL_MULTICOMPONENT_QUICKLINKGROUP_BOTTOM = 'notifications-multicomponent-quicklinkgroup-bottom';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_AAL_MULTICOMPONENT_QUICKLINKGROUP_BOTTOM],
        );
    }

    public function getConditionalOnDataFieldSubmodules(array $module): array
    {
        $ret = parent::getConditionalOnDataFieldSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_AAL_MULTICOMPONENT_QUICKLINKGROUP_BOTTOM:
                $ret = array_merge_recursive(
                    $ret,
                    \PoP\Root\App::applyFilters(
                        'PoP_Module_Processor_MultipleComponentLayouts:modules',
                        array(),
                        $module
                    )
                );
                break;
        }

        return $ret;
    }
}



