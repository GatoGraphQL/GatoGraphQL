<?php

class PoP_PostsCreation_Module_Processor_CreateUpdatePostBlocks extends PoP_Module_Processor_AddEditContentBlocksBase
{
    public const MODULE_BLOCK_POST_UPDATE = 'block-post-update';
    public const MODULE_BLOCK_POST_CREATE = 'block-post-create';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_POST_UPDATE],
            [self::class, self::MODULE_BLOCK_POST_CREATE],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        $routes = array(
            self::MODULE_BLOCK_POST_CREATE => POP_POSTSCREATION_ROUTE_ADDPOST,
            self::MODULE_BLOCK_POST_UPDATE => POP_POSTSCREATION_ROUTE_EDITPOST,
        );
        return $routes[$module[1]] ?? parent::getRelevantRoute($module, $props);
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        $block_inners = array(
            self::MODULE_BLOCK_POST_UPDATE => [PoP_PostsCreation_Module_Processor_CreateUpdatePostDataloads::class, PoP_PostsCreation_Module_Processor_CreateUpdatePostDataloads::MODULE_DATALOAD_POST_UPDATE],
            self::MODULE_BLOCK_POST_CREATE => [PoP_PostsCreation_Module_Processor_CreateUpdatePostDataloads::class, PoP_PostsCreation_Module_Processor_CreateUpdatePostDataloads::MODULE_DATALOAD_POST_CREATE],
        );
        if ($block_inner = $block_inners[$module[1]]) {
            $ret[] = $block_inner;
        }
    
        return $ret;
    }

    protected function isCreate(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_POST_CREATE:
                return true;
        }

        return parent::isCreate($module);
    }
    protected function isUpdate(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_POST_UPDATE:
                return true;
        }

        return parent::isUpdate($module);
    }

    public function initModelProps(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_POST_UPDATE:
            case self::MODULE_BLOCK_POST_CREATE:
                $this->appendProp($module, $props, 'class', 'block-createupdate-contentpost');
                if (PoP_Application_Utils::getAddcontentTarget() == POP_TARGET_ADDONS) {
                    $this->appendProp($module, $props, 'class', 'addons-nocontrols');
                }
                break;
        }
        
        parent::initModelProps($module, $props);
    }
}


