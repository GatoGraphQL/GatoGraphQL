<?php

use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\ConditionalLeafComponentField;

class PoP_Module_Processor_MultipleComponentLayouts extends PoP_Module_Processor_MultiplesBase
{
    public final const COMPONENT_AAL_MULTICOMPONENT_QUICKLINKGROUP_BOTTOM = 'notifications-multicomponent-quicklinkgroup-bottom';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_AAL_MULTICOMPONENT_QUICKLINKGROUP_BOTTOM,
        );
    }

    /**
     * @return ConditionalLeafComponentField[]
     */
    public function getConditionalLeafComponentFields(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getConditionalLeafComponentFields($component);

        switch ($component->name) {
            case self::COMPONENT_AAL_MULTICOMPONENT_QUICKLINKGROUP_BOTTOM:
                $ret = array_merge(
                    $ret,
                    \PoP\Root\App::applyFilters(
                        'PoP_Module_Processor_MultipleComponentLayouts:modules',
                        [],
                        $component
                    )
                );
                break;
        }

        return $ret;
    }
}



