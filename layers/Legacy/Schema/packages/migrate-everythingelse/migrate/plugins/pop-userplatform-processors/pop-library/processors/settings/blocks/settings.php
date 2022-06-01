<?php

class PoP_Module_Processor_CustomSettingsBlocks extends PoP_Module_Processor_BlocksBase
{
    public final const COMPONENT_BLOCK_SETTINGS = 'block-settings';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_BLOCK_SETTINGS,
        );
    }

    public function getRelevantRoute(\PoP\ComponentModel\Component\Component $component, array &$props): ?string
    {
        return match($component->name) {
            self::COMPONENT_BLOCK_SETTINGS => POP_USERPLATFORM_ROUTE_SETTINGS,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_BLOCK_SETTINGS:
                $ret[] = [PoP_Module_Processor_CustomSettingsDataloads::class, PoP_Module_Processor_CustomSettingsDataloads::COMPONENT_DATALOAD_SETTINGS];
                break;
        }

        return $ret;
    }
}



