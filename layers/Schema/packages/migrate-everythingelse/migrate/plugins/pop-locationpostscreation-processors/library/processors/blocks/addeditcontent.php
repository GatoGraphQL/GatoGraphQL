<?php

class GD_Custom_EM_Module_Processor_CreateUpdatePostBlocks extends PoP_Module_Processor_AddEditContentBlocksBase
{
    public const MODULE_BLOCK_LOCATIONPOST_UPDATE = 'block-locationpost-update';
    public const MODULE_BLOCK_LOCATIONPOST_CREATE = 'block-locationpost-create';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_LOCATIONPOST_UPDATE],
            [self::class, self::MODULE_BLOCK_LOCATIONPOST_CREATE],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        $routes = array(
            self::MODULE_BLOCK_LOCATIONPOST_CREATE => POP_LOCATIONPOSTSCREATION_ROUTE_ADDLOCATIONPOST,
            self::MODULE_BLOCK_LOCATIONPOST_UPDATE => POP_LOCATIONPOSTSCREATION_ROUTE_EDITLOCATIONPOST,
        );
        return $routes[$module[1]] ?? parent::getRelevantRoute($module, $props);
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        $block_inners = array(
            self::MODULE_BLOCK_LOCATIONPOST_UPDATE => [GD_Custom_EM_Module_Processor_CreateUpdatePostDataloads::class, GD_Custom_EM_Module_Processor_CreateUpdatePostDataloads::MODULE_DATALOAD_LOCATIONPOST_UPDATE],
            self::MODULE_BLOCK_LOCATIONPOST_CREATE => [GD_Custom_EM_Module_Processor_CreateUpdatePostDataloads::class, GD_Custom_EM_Module_Processor_CreateUpdatePostDataloads::MODULE_DATALOAD_LOCATIONPOST_CREATE],
        );
        if ($block_inner = $block_inners[$module[1]] ?? null) {
            $ret[] = $block_inner;
        }

        return $ret;
    }

    protected function isCreate(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_LOCATIONPOST_CREATE:
                return true;
        }

        return parent::isCreate($module);
    }
    protected function isUpdate(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_LOCATIONPOST_UPDATE:
                return true;
        }

        return parent::isUpdate($module);
    }

    public function initModelProps(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_LOCATIONPOST_UPDATE:
            case self::MODULE_BLOCK_LOCATIONPOST_CREATE:
                if (PoP_Application_Utils::getAddcontentTarget() == POP_TARGET_ADDONS) {
                    $this->appendProp($module, $props, 'class', 'addons-nocontrols');
                }
                break;
        }

        parent::initModelProps($module, $props);
    }
}


