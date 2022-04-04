<?php

class PoP_EventLinksCreation_Module_Processor_CreateUpdatePostBlocks extends PoP_Module_Processor_AddEditContentBlocksBase
{
    public final const MODULE_BLOCK_EVENTLINK_UPDATE = 'block-eventlink-update';
    public final const MODULE_BLOCK_EVENTLINK_CREATE = 'block-eventlink-create';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_EVENTLINK_UPDATE],
            [self::class, self::MODULE_BLOCK_EVENTLINK_CREATE],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_BLOCK_EVENTLINK_CREATE => POP_EVENTLINKSCREATION_ROUTE_ADDEVENTLINK,
            self::MODULE_BLOCK_EVENTLINK_UPDATE => POP_EVENTLINKSCREATION_ROUTE_EDITEVENTLINK,
            default => parent::getRelevantRoute($module, $props),
        };
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        $block_inners = array(
            self::MODULE_BLOCK_EVENTLINK_UPDATE => [PoP_EventLinksCreation_Module_Processor_CreateUpdatePostDataloads::class, PoP_EventLinksCreation_Module_Processor_CreateUpdatePostDataloads::MODULE_DATALOAD_EVENTLINK_UPDATE],
            self::MODULE_BLOCK_EVENTLINK_CREATE => [PoP_EventLinksCreation_Module_Processor_CreateUpdatePostDataloads::class, PoP_EventLinksCreation_Module_Processor_CreateUpdatePostDataloads::MODULE_DATALOAD_EVENTLINK_CREATE],
        );
        if ($block_inner = $block_inners[$module[1]] ?? null) {
            $ret[] = $block_inner;
        }

        return $ret;
    }

    protected function isCreate(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_EVENTLINK_CREATE:
                return true;
        }

        return parent::isCreate($module);
    }
    protected function isUpdate(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_EVENTLINK_UPDATE:
                return true;
        }

        return parent::isUpdate($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_EVENTLINK_UPDATE:
            case self::MODULE_BLOCK_EVENTLINK_CREATE:
                if (PoP_Application_Utils::getAddcontentTarget() == POP_TARGET_ADDONS) {
                    $this->appendProp($module, $props, 'class', 'addons-nocontrols');
                }
                break;
        }

        parent::initModelProps($module, $props);
    }
}


