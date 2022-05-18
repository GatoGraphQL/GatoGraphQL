<?php

class GD_EM_Module_Processor_CreateUpdatePostBlocks extends PoP_Module_Processor_AddEditContentBlocksBase
{
    public final const MODULE_BLOCK_EVENT_UPDATE = 'block-event-update';
    public final const MODULE_BLOCK_EVENT_CREATE = 'block-event-create';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_EVENT_UPDATE],
            [self::class, self::MODULE_BLOCK_EVENT_CREATE],
        );
    }

    public function getRelevantRoute(array $componentVariation, array &$props): ?string
    {
        return match($componentVariation[1]) {
            self::MODULE_BLOCK_EVENT_CREATE => POP_EVENTSCREATION_ROUTE_ADDEVENT,
            self::MODULE_BLOCK_EVENT_UPDATE => POP_EVENTSCREATION_ROUTE_EDITEVENT,
            default => parent::getRelevantRoute($componentVariation, $props),
        };
    }

    protected function getInnerSubmodules(array $componentVariation): array
    {
        $ret = parent::getInnerSubmodules($componentVariation);

        $block_inners = array(
            self::MODULE_BLOCK_EVENT_UPDATE => [GD_EM_Module_Processor_CreateUpdatePostDataloads::class, GD_EM_Module_Processor_CreateUpdatePostDataloads::MODULE_DATALOAD_EVENT_UPDATE],
            self::MODULE_BLOCK_EVENT_CREATE => [GD_EM_Module_Processor_CreateUpdatePostDataloads::class, GD_EM_Module_Processor_CreateUpdatePostDataloads::MODULE_DATALOAD_EVENT_CREATE],
        );
        if ($block_inner = $block_inners[$componentVariation[1]] ?? null) {
            $ret[] = $block_inner;
        }

        return $ret;
    }

    protected function isCreate(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_EVENT_CREATE:
                return true;
        }

        return parent::isCreate($componentVariation);
    }
    protected function isUpdate(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_EVENT_UPDATE:
                return true;
        }

        return parent::isUpdate($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_EVENT_UPDATE:
            case self::MODULE_BLOCK_EVENT_CREATE:
                if (PoP_Application_Utils::getAddcontentTarget() == POP_TARGET_ADDONS) {
                    $this->appendProp($componentVariation, $props, 'class', 'addons-nocontrols');
                }
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



