<?php

use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\ConditionalLeafModuleField;

class PoP_Module_Processor_MultipleComponentLayouts extends PoP_Module_Processor_MultiplesBase
{
    public final const MODULE_AAL_MULTICOMPONENT_QUICKLINKGROUP_BOTTOM = 'notifications-multicomponent-quicklinkgroup-bottom';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_AAL_MULTICOMPONENT_QUICKLINKGROUP_BOTTOM],
        );
    }

    /**
     * @return ConditionalLeafModuleField[]
     */
    public function getConditionalOnDataFieldSubmodules(array $module): array
    {
        $ret = parent::getConditionalOnDataFieldSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_AAL_MULTICOMPONENT_QUICKLINKGROUP_BOTTOM:
                $ret = array_merge(
                    $ret,
                    \PoP\Root\App::applyFilters(
                        'PoP_Module_Processor_MultipleComponentLayouts:modules',
                        [],
                        $module
                    )
                );
                break;
        }

        return $ret;
    }
}



