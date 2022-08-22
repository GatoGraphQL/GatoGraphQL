<?php

class PoP_AddHighlights_Module_Processor_CreateUpdatePostBlocks extends PoP_Module_Processor_AddEditContentBlocksBase
{
    public final const COMPONENT_BLOCK_HIGHLIGHT_UPDATE = 'block-highlight-update';
    public final const COMPONENT_BLOCK_HIGHLIGHT_CREATE = 'block-highlight-create';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_BLOCK_HIGHLIGHT_UPDATE,
            self::COMPONENT_BLOCK_HIGHLIGHT_CREATE,
        );
    }

    public function getRelevantRoute(\PoP\ComponentModel\Component\Component $component, array &$props): ?string
    {
        return match($component->name) {
            self::COMPONENT_BLOCK_HIGHLIGHT_CREATE => POP_ADDHIGHLIGHTS_ROUTE_ADDHIGHLIGHT,
            self::COMPONENT_BLOCK_HIGHLIGHT_UPDATE => POP_ADDHIGHLIGHTS_ROUTE_EDITHIGHLIGHT,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        $block_inners = array(
            self::COMPONENT_BLOCK_HIGHLIGHT_UPDATE => [PoP_AddHighlights_Module_Processor_CreateUpdatePostDataloads::class, PoP_AddHighlights_Module_Processor_CreateUpdatePostDataloads::COMPONENT_DATALOAD_HIGHLIGHT_UPDATE],
            self::COMPONENT_BLOCK_HIGHLIGHT_CREATE => [PoP_AddHighlights_Module_Processor_CreateUpdatePostDataloads::class, PoP_AddHighlights_Module_Processor_CreateUpdatePostDataloads::COMPONENT_DATALOAD_HIGHLIGHT_CREATE],
        );
        if ($block_inner = $block_inners[$component->name] ?? null) {
            $ret[] = $block_inner;
        }

        return $ret;
    }

    protected function isCreate(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_BLOCK_HIGHLIGHT_CREATE:
                return true;
        }

        return parent::isCreate($component);
    }
    protected function isUpdate(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_BLOCK_HIGHLIGHT_UPDATE:
                return true;
        }

        return parent::isUpdate($component);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_BLOCK_HIGHLIGHT_UPDATE:
            case self::COMPONENT_BLOCK_HIGHLIGHT_CREATE:
                $this->appendProp($component, $props, 'class', 'addons-nocontrols');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



