<?php

class PoP_AddHighlights_Module_Processor_CreateUpdatePostBlocks extends PoP_Module_Processor_AddEditContentBlocksBase
{
    public final const MODULE_BLOCK_HIGHLIGHT_UPDATE = 'block-highlight-update';
    public final const MODULE_BLOCK_HIGHLIGHT_CREATE = 'block-highlight-create';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_HIGHLIGHT_UPDATE],
            [self::class, self::MODULE_BLOCK_HIGHLIGHT_CREATE],
        );
    }

    public function getRelevantRoute(array $componentVariation, array &$props): ?string
    {
        return match($componentVariation[1]) {
            self::MODULE_BLOCK_HIGHLIGHT_CREATE => POP_ADDHIGHLIGHTS_ROUTE_ADDHIGHLIGHT,
            self::MODULE_BLOCK_HIGHLIGHT_UPDATE => POP_ADDHIGHLIGHTS_ROUTE_EDITHIGHLIGHT,
            default => parent::getRelevantRoute($componentVariation, $props),
        };
    }

    protected function getInnerSubmodules(array $componentVariation): array
    {
        $ret = parent::getInnerSubmodules($componentVariation);

        $block_inners = array(
            self::MODULE_BLOCK_HIGHLIGHT_UPDATE => [PoP_AddHighlights_Module_Processor_CreateUpdatePostDataloads::class, PoP_AddHighlights_Module_Processor_CreateUpdatePostDataloads::MODULE_DATALOAD_HIGHLIGHT_UPDATE],
            self::MODULE_BLOCK_HIGHLIGHT_CREATE => [PoP_AddHighlights_Module_Processor_CreateUpdatePostDataloads::class, PoP_AddHighlights_Module_Processor_CreateUpdatePostDataloads::MODULE_DATALOAD_HIGHLIGHT_CREATE],
        );
        if ($block_inner = $block_inners[$componentVariation[1]] ?? null) {
            $ret[] = $block_inner;
        }

        return $ret;
    }

    protected function isCreate(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_HIGHLIGHT_CREATE:
                return true;
        }

        return parent::isCreate($componentVariation);
    }
    protected function isUpdate(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_HIGHLIGHT_UPDATE:
                return true;
        }

        return parent::isUpdate($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_HIGHLIGHT_UPDATE:
            case self::MODULE_BLOCK_HIGHLIGHT_CREATE:
                $this->appendProp($componentVariation, $props, 'class', 'addons-nocontrols');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



