<?php

class PoP_PostsCreation_Module_Processor_CreateUpdatePostBlocks extends PoP_Module_Processor_AddEditContentBlocksBase
{
    public final const MODULE_BLOCK_POST_UPDATE = 'block-post-update';
    public final const MODULE_BLOCK_POST_CREATE = 'block-post-create';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_POST_UPDATE],
            [self::class, self::COMPONENT_BLOCK_POST_CREATE],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_BLOCK_POST_CREATE => POP_POSTSCREATION_ROUTE_ADDPOST,
            self::COMPONENT_BLOCK_POST_UPDATE => POP_POSTSCREATION_ROUTE_EDITPOST,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        $block_inners = array(
            self::COMPONENT_BLOCK_POST_UPDATE => [PoP_PostsCreation_Module_Processor_CreateUpdatePostDataloads::class, PoP_PostsCreation_Module_Processor_CreateUpdatePostDataloads::COMPONENT_DATALOAD_POST_UPDATE],
            self::COMPONENT_BLOCK_POST_CREATE => [PoP_PostsCreation_Module_Processor_CreateUpdatePostDataloads::class, PoP_PostsCreation_Module_Processor_CreateUpdatePostDataloads::COMPONENT_DATALOAD_POST_CREATE],
        );
        if ($block_inner = $block_inners[$component[1]] ?? null) {
            $ret[] = $block_inner;
        }

        return $ret;
    }

    protected function isCreate(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_POST_CREATE:
                return true;
        }

        return parent::isCreate($component);
    }
    protected function isUpdate(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_POST_UPDATE:
                return true;
        }

        return parent::isUpdate($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_POST_UPDATE:
            case self::COMPONENT_BLOCK_POST_CREATE:
                $this->appendProp($component, $props, 'class', 'block-createupdate-contentpost');
                if (PoP_Application_Utils::getAddcontentTarget() == POP_TARGET_ADDONS) {
                    $this->appendProp($component, $props, 'class', 'addons-nocontrols');
                }
                break;
        }

        parent::initModelProps($component, $props);
    }
}


