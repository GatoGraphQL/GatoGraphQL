<?php

class GD_Custom_EM_Module_Processor_CreateUpdatePostBlocks extends PoP_Module_Processor_AddEditContentBlocksBase
{
    public final const MODULE_BLOCK_LOCATIONPOST_UPDATE = 'block-locationpost-update';
    public final const MODULE_BLOCK_LOCATIONPOST_CREATE = 'block-locationpost-create';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_LOCATIONPOST_UPDATE],
            [self::class, self::COMPONENT_BLOCK_LOCATIONPOST_CREATE],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_BLOCK_LOCATIONPOST_CREATE => POP_LOCATIONPOSTSCREATION_ROUTE_ADDLOCATIONPOST,
            self::COMPONENT_BLOCK_LOCATIONPOST_UPDATE => POP_LOCATIONPOSTSCREATION_ROUTE_EDITLOCATIONPOST,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        $block_inners = array(
            self::COMPONENT_BLOCK_LOCATIONPOST_UPDATE => [GD_Custom_EM_Module_Processor_CreateUpdatePostDataloads::class, GD_Custom_EM_Module_Processor_CreateUpdatePostDataloads::COMPONENT_DATALOAD_LOCATIONPOST_UPDATE],
            self::COMPONENT_BLOCK_LOCATIONPOST_CREATE => [GD_Custom_EM_Module_Processor_CreateUpdatePostDataloads::class, GD_Custom_EM_Module_Processor_CreateUpdatePostDataloads::COMPONENT_DATALOAD_LOCATIONPOST_CREATE],
        );
        if ($block_inner = $block_inners[$component[1]] ?? null) {
            $ret[] = $block_inner;
        }

        return $ret;
    }

    protected function isCreate(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_LOCATIONPOST_CREATE:
                return true;
        }

        return parent::isCreate($component);
    }
    protected function isUpdate(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_LOCATIONPOST_UPDATE:
                return true;
        }

        return parent::isUpdate($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_LOCATIONPOST_UPDATE:
            case self::COMPONENT_BLOCK_LOCATIONPOST_CREATE:
                if (PoP_Application_Utils::getAddcontentTarget() == POP_TARGET_ADDONS) {
                    $this->appendProp($component, $props, 'class', 'addons-nocontrols');
                }
                break;
        }

        parent::initModelProps($component, $props);
    }
}


