<?php

class PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostBlocks extends PoP_Module_Processor_AddEditContentBlocksBase
{
    public final const MODULE_BLOCK_CONTENTPOSTLINK_UPDATE = 'block-postlink-update';
    public final const MODULE_BLOCK_CONTENTPOSTLINK_CREATE = 'block-postlink-create';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_CONTENTPOSTLINK_UPDATE],
            [self::class, self::MODULE_BLOCK_CONTENTPOSTLINK_CREATE],
        );
    }

    public function getRelevantRoute(array $componentVariation, array &$props): ?string
    {
        return match($componentVariation[1]) {
            self::MODULE_BLOCK_CONTENTPOSTLINK_CREATE => POP_CONTENTPOSTLINKSCREATION_ROUTE_ADDCONTENTPOSTLINK,
            self::MODULE_BLOCK_CONTENTPOSTLINK_UPDATE => POP_CONTENTPOSTLINKSCREATION_ROUTE_EDITCONTENTPOSTLINK,
            default => parent::getRelevantRoute($componentVariation, $props),
        };
    }

    protected function getInnerSubmodules(array $componentVariation): array
    {
        $ret = parent::getInnerSubmodules($componentVariation);

        $block_inners = array(
            self::MODULE_BLOCK_CONTENTPOSTLINK_UPDATE => [PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostDataloads::class, PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostDataloads::MODULE_DATALOAD_CONTENTPOSTLINK_UPDATE],
            self::MODULE_BLOCK_CONTENTPOSTLINK_CREATE => [PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostDataloads::class, PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostDataloads::MODULE_DATALOAD_CONTENTPOSTLINK_CREATE],
        );
        if ($block_inner = $block_inners[$componentVariation[1]] ?? null) {
            $ret[] = $block_inner;
        }

        return $ret;
    }

    protected function isCreate(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_CONTENTPOSTLINK_CREATE:
                return true;
        }

        return parent::isCreate($componentVariation);
    }
    protected function isUpdate(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_CONTENTPOSTLINK_UPDATE:
                return true;
        }

        return parent::isUpdate($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_CONTENTPOSTLINK_UPDATE:
            case self::MODULE_BLOCK_CONTENTPOSTLINK_CREATE:
                $this->appendProp($componentVariation, $props, 'class', 'block-createupdate-contentpost');
                if (PoP_Application_Utils::getAddcontentTarget() == POP_TARGET_ADDONS) {
                    $this->appendProp($componentVariation, $props, 'class', 'addons-nocontrols');
                }
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



