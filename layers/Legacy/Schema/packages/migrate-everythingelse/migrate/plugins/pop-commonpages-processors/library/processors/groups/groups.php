<?php

class GD_CommonPages_Module_Processor_CustomGroups extends PoP_Module_Processor_MultiplesBase
{
    public final const COMPONENT_GROUP_WHOWEARE = 'group-whoweare';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_GROUP_WHOWEARE],
        );
    }

    public function getSubcomponents(array $component): array
    {
        $ret = parent::getSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_GROUP_WHOWEARE:
                // Allow to override with custom blocks
                $ret = array_merge(
                    $ret,
                    \PoP\Root\App::applyFilters(
                        'PoP_Module_Processor_CustomGroups:modules:whoweare',
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


