<?php

class PoP_Module_Processor_CustomSettingsBlocks extends PoP_Module_Processor_BlocksBase
{
    public final const COMPONENT_BLOCK_SETTINGS = 'block-settings';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_SETTINGS],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_BLOCK_SETTINGS => POP_USERPLATFORM_ROUTE_SETTINGS,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubcomponents(array $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_BLOCK_SETTINGS:
                $ret[] = [PoP_Module_Processor_CustomSettingsDataloads::class, PoP_Module_Processor_CustomSettingsDataloads::COMPONENT_DATALOAD_SETTINGS];
                break;
        }

        return $ret;
    }
}



