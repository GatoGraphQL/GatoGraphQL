<?php

class PoP_AddHighlights_Module_Processor_CreateUpdatePostBlocks extends PoP_Module_Processor_AddEditContentBlocksBase
{
    public final const MODULE_BLOCK_HIGHLIGHT_UPDATE = 'block-highlight-update';
    public final const MODULE_BLOCK_HIGHLIGHT_CREATE = 'block-highlight-create';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_HIGHLIGHT_UPDATE],
            [self::class, self::MODULE_BLOCK_HIGHLIGHT_CREATE],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::MODULE_BLOCK_HIGHLIGHT_CREATE => POP_ADDHIGHLIGHTS_ROUTE_ADDHIGHLIGHT,
            self::MODULE_BLOCK_HIGHLIGHT_UPDATE => POP_ADDHIGHLIGHTS_ROUTE_EDITHIGHLIGHT,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        $block_inners = array(
            self::MODULE_BLOCK_HIGHLIGHT_UPDATE => [PoP_AddHighlights_Module_Processor_CreateUpdatePostDataloads::class, PoP_AddHighlights_Module_Processor_CreateUpdatePostDataloads::MODULE_DATALOAD_HIGHLIGHT_UPDATE],
            self::MODULE_BLOCK_HIGHLIGHT_CREATE => [PoP_AddHighlights_Module_Processor_CreateUpdatePostDataloads::class, PoP_AddHighlights_Module_Processor_CreateUpdatePostDataloads::MODULE_DATALOAD_HIGHLIGHT_CREATE],
        );
        if ($block_inner = $block_inners[$component[1]] ?? null) {
            $ret[] = $block_inner;
        }

        return $ret;
    }

    protected function isCreate(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_BLOCK_HIGHLIGHT_CREATE:
                return true;
        }

        return parent::isCreate($component);
    }
    protected function isUpdate(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_BLOCK_HIGHLIGHT_UPDATE:
                return true;
        }

        return parent::isUpdate($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::MODULE_BLOCK_HIGHLIGHT_UPDATE:
            case self::MODULE_BLOCK_HIGHLIGHT_CREATE:
                $this->appendProp($component, $props, 'class', 'addons-nocontrols');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



