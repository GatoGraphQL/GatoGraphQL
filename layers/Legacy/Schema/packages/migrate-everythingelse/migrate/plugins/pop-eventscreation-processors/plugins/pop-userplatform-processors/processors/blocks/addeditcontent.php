<?php

class GD_EM_Module_Processor_CreateUpdatePostBlocks extends PoP_Module_Processor_AddEditContentBlocksBase
{
    public final const MODULE_BLOCK_EVENT_UPDATE = 'block-event-update';
    public final const MODULE_BLOCK_EVENT_CREATE = 'block-event-create';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_EVENT_UPDATE],
            [self::class, self::MODULE_BLOCK_EVENT_CREATE],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::MODULE_BLOCK_EVENT_CREATE => POP_EVENTSCREATION_ROUTE_ADDEVENT,
            self::MODULE_BLOCK_EVENT_UPDATE => POP_EVENTSCREATION_ROUTE_EDITEVENT,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        $block_inners = array(
            self::MODULE_BLOCK_EVENT_UPDATE => [GD_EM_Module_Processor_CreateUpdatePostDataloads::class, GD_EM_Module_Processor_CreateUpdatePostDataloads::MODULE_DATALOAD_EVENT_UPDATE],
            self::MODULE_BLOCK_EVENT_CREATE => [GD_EM_Module_Processor_CreateUpdatePostDataloads::class, GD_EM_Module_Processor_CreateUpdatePostDataloads::MODULE_DATALOAD_EVENT_CREATE],
        );
        if ($block_inner = $block_inners[$component[1]] ?? null) {
            $ret[] = $block_inner;
        }

        return $ret;
    }

    protected function isCreate(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_BLOCK_EVENT_CREATE:
                return true;
        }

        return parent::isCreate($component);
    }
    protected function isUpdate(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_BLOCK_EVENT_UPDATE:
                return true;
        }

        return parent::isUpdate($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::MODULE_BLOCK_EVENT_UPDATE:
            case self::MODULE_BLOCK_EVENT_CREATE:
                if (PoP_Application_Utils::getAddcontentTarget() == POP_TARGET_ADDONS) {
                    $this->appendProp($component, $props, 'class', 'addons-nocontrols');
                }
                break;
        }

        parent::initModelProps($component, $props);
    }
}



