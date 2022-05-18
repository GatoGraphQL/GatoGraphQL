<?php

class PoP_EventLinksCreation_Module_Processor_CreateUpdatePostBlocks extends PoP_Module_Processor_AddEditContentBlocksBase
{
    public final const COMPONENT_BLOCK_EVENTLINK_UPDATE = 'block-eventlink-update';
    public final const COMPONENT_BLOCK_EVENTLINK_CREATE = 'block-eventlink-create';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_EVENTLINK_UPDATE],
            [self::class, self::COMPONENT_BLOCK_EVENTLINK_CREATE],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_BLOCK_EVENTLINK_CREATE => POP_EVENTLINKSCREATION_ROUTE_ADDEVENTLINK,
            self::COMPONENT_BLOCK_EVENTLINK_UPDATE => POP_EVENTLINKSCREATION_ROUTE_EDITEVENTLINK,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        $block_inners = array(
            self::COMPONENT_BLOCK_EVENTLINK_UPDATE => [PoP_EventLinksCreation_Module_Processor_CreateUpdatePostDataloads::class, PoP_EventLinksCreation_Module_Processor_CreateUpdatePostDataloads::COMPONENT_DATALOAD_EVENTLINK_UPDATE],
            self::COMPONENT_BLOCK_EVENTLINK_CREATE => [PoP_EventLinksCreation_Module_Processor_CreateUpdatePostDataloads::class, PoP_EventLinksCreation_Module_Processor_CreateUpdatePostDataloads::COMPONENT_DATALOAD_EVENTLINK_CREATE],
        );
        if ($block_inner = $block_inners[$component[1]] ?? null) {
            $ret[] = $block_inner;
        }

        return $ret;
    }

    protected function isCreate(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_EVENTLINK_CREATE:
                return true;
        }

        return parent::isCreate($component);
    }
    protected function isUpdate(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_EVENTLINK_UPDATE:
                return true;
        }

        return parent::isUpdate($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_EVENTLINK_UPDATE:
            case self::COMPONENT_BLOCK_EVENTLINK_CREATE:
                if (PoP_Application_Utils::getAddcontentTarget() == POP_TARGET_ADDONS) {
                    $this->appendProp($component, $props, 'class', 'addons-nocontrols');
                }
                break;
        }

        parent::initModelProps($component, $props);
    }
}


