<?php

class GD_CommonPages_Module_Processor_CustomGroups extends PoP_Module_Processor_MultiplesBase
{
    public final const COMPONENT_GROUP_WHOWEARE = 'group-whoweare';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_GROUP_WHOWEARE,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_GROUP_WHOWEARE:
                // Allow to override with custom blocks
                $ret = array_merge(
                    $ret,
                    \PoP\Root\App::applyFilters(
                        'PoP_Module_Processor_CustomGroups:components:whoweare',
                        array(
                            [GD_Custom_Module_Processor_CustomSectionBlocks::class, GD_Custom_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_WHOWEARE_SCROLL_DETAILS]
                        )
                    )
                );
                break;
        }

        return $ret;
    }
}


