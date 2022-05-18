<?php

class PoP_Module_Processor_CustomSettingsBlocks extends PoP_Module_Processor_BlocksBase
{
    public final const MODULE_BLOCK_SETTINGS = 'block-settings';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_SETTINGS],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::MODULE_BLOCK_SETTINGS => POP_USERPLATFORM_ROUTE_SETTINGS,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        switch ($component[1]) {
            case self::MODULE_BLOCK_SETTINGS:
                $ret[] = [PoP_Module_Processor_CustomSettingsDataloads::class, PoP_Module_Processor_CustomSettingsDataloads::MODULE_DATALOAD_SETTINGS];
                break;
        }

        return $ret;
    }
}



