<?php

class PoP_LocationPostLinksCreation_Module_Processor_CreateUpdatePostBlocks extends PoP_Module_Processor_AddEditContentBlocksBase
{
    public const MODULE_BLOCK_LOCATIONPOSTLINK_UPDATE = 'block-locationpostlink-update';
    public const MODULE_BLOCK_LOCATIONPOSTLINK_CREATE = 'block-locationpostlink-create';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_LOCATIONPOSTLINK_UPDATE],
            [self::class, self::MODULE_BLOCK_LOCATIONPOSTLINK_CREATE],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        $routes = array(
            self::MODULE_BLOCK_LOCATIONPOSTLINK_CREATE => POP_LOCATIONPOSTLINKSCREATION_ROUTE_ADDLOCATIONPOSTLINK,
            self::MODULE_BLOCK_LOCATIONPOSTLINK_UPDATE => POP_LOCATIONPOSTLINKSCREATION_ROUTE_EDITLOCATIONPOSTLINK,
        );
        return $routes[$module[1]] ?? parent::getRelevantRoute($module, $props);
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        $block_inners = array(
            self::MODULE_BLOCK_LOCATIONPOSTLINK_UPDATE => [PoP_LocationPostLinksCreation_Module_Processor_CreateUpdatePostDataloads::class, PoP_LocationPostLinksCreation_Module_Processor_CreateUpdatePostDataloads::MODULE_DATALOAD_LOCATIONPOSTLINK_UPDATE],
            self::MODULE_BLOCK_LOCATIONPOSTLINK_CREATE => [PoP_LocationPostLinksCreation_Module_Processor_CreateUpdatePostDataloads::class, PoP_LocationPostLinksCreation_Module_Processor_CreateUpdatePostDataloads::MODULE_DATALOAD_LOCATIONPOSTLINK_CREATE],
        );
        if ($block_inner = $block_inners[$module[1]] ?? null) {
            $ret[] = $block_inner;
        }

        return $ret;
    }

    protected function isCreate(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_LOCATIONPOSTLINK_CREATE:
                return true;
        }

        return parent::isCreate($module);
    }
    protected function isUpdate(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_LOCATIONPOSTLINK_UPDATE:
                return true;
        }

        return parent::isUpdate($module);
    }

    public function initModelProps(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_LOCATIONPOSTLINK_UPDATE:
            case self::MODULE_BLOCK_LOCATIONPOSTLINK_CREATE:
                if (PoP_Application_Utils::getAddcontentTarget() == POP_TARGET_ADDONS) {
                    $this->appendProp($module, $props, 'class', 'addons-nocontrols');
                }
                break;
        }

        parent::initModelProps($module, $props);
    }
}


