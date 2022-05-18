<?php

class PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostBlocks extends PoP_Module_Processor_AddEditContentBlocksBase
{
    public final const COMPONENT_BLOCK_CONTENTPOSTLINK_UPDATE = 'block-postlink-update';
    public final const COMPONENT_BLOCK_CONTENTPOSTLINK_CREATE = 'block-postlink-create';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_CONTENTPOSTLINK_UPDATE],
            [self::class, self::COMPONENT_BLOCK_CONTENTPOSTLINK_CREATE],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_BLOCK_CONTENTPOSTLINK_CREATE => POP_CONTENTPOSTLINKSCREATION_ROUTE_ADDCONTENTPOSTLINK,
            self::COMPONENT_BLOCK_CONTENTPOSTLINK_UPDATE => POP_CONTENTPOSTLINKSCREATION_ROUTE_EDITCONTENTPOSTLINK,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        $block_inners = array(
            self::COMPONENT_BLOCK_CONTENTPOSTLINK_UPDATE => [PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostDataloads::class, PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostDataloads::COMPONENT_DATALOAD_CONTENTPOSTLINK_UPDATE],
            self::COMPONENT_BLOCK_CONTENTPOSTLINK_CREATE => [PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostDataloads::class, PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostDataloads::COMPONENT_DATALOAD_CONTENTPOSTLINK_CREATE],
        );
        if ($block_inner = $block_inners[$component[1]] ?? null) {
            $ret[] = $block_inner;
        }

        return $ret;
    }

    protected function isCreate(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_CONTENTPOSTLINK_CREATE:
                return true;
        }

        return parent::isCreate($component);
    }
    protected function isUpdate(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_CONTENTPOSTLINK_UPDATE:
                return true;
        }

        return parent::isUpdate($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_CONTENTPOSTLINK_UPDATE:
            case self::COMPONENT_BLOCK_CONTENTPOSTLINK_CREATE:
                $this->appendProp($component, $props, 'class', 'block-createupdate-contentpost');
                if (PoP_Application_Utils::getAddcontentTarget() == POP_TARGET_ADDONS) {
                    $this->appendProp($component, $props, 'class', 'addons-nocontrols');
                }
                break;
        }

        parent::initModelProps($component, $props);
    }
}



