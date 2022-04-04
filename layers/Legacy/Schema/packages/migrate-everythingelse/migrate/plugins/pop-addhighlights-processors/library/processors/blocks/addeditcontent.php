<?php

class PoP_AddHighlights_Module_Processor_CreateUpdatePostBlocks extends PoP_Module_Processor_AddEditContentBlocksBase
{
    public final const MODULE_BLOCK_HIGHLIGHT_UPDATE = 'block-highlight-update';
    public final const MODULE_BLOCK_HIGHLIGHT_CREATE = 'block-highlight-create';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_HIGHLIGHT_UPDATE],
            [self::class, self::MODULE_BLOCK_HIGHLIGHT_CREATE],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_BLOCK_HIGHLIGHT_CREATE => POP_ADDHIGHLIGHTS_ROUTE_ADDHIGHLIGHT,
            self::MODULE_BLOCK_HIGHLIGHT_UPDATE => POP_ADDHIGHLIGHTS_ROUTE_EDITHIGHLIGHT,
            default => parent::getRelevantRoute($module, $props),
        };
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        $block_inners = array(
            self::MODULE_BLOCK_HIGHLIGHT_UPDATE => [PoP_AddHighlights_Module_Processor_CreateUpdatePostDataloads::class, PoP_AddHighlights_Module_Processor_CreateUpdatePostDataloads::MODULE_DATALOAD_HIGHLIGHT_UPDATE],
            self::MODULE_BLOCK_HIGHLIGHT_CREATE => [PoP_AddHighlights_Module_Processor_CreateUpdatePostDataloads::class, PoP_AddHighlights_Module_Processor_CreateUpdatePostDataloads::MODULE_DATALOAD_HIGHLIGHT_CREATE],
        );
        if ($block_inner = $block_inners[$module[1]] ?? null) {
            $ret[] = $block_inner;
        }

        return $ret;
    }

    protected function isCreate(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_HIGHLIGHT_CREATE:
                return true;
        }

        return parent::isCreate($module);
    }
    protected function isUpdate(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_HIGHLIGHT_UPDATE:
                return true;
        }

        return parent::isUpdate($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_HIGHLIGHT_UPDATE:
            case self::MODULE_BLOCK_HIGHLIGHT_CREATE:
                $this->appendProp($module, $props, 'class', 'addons-nocontrols');
                break;
        }

        parent::initModelProps($module, $props);
    }
}



