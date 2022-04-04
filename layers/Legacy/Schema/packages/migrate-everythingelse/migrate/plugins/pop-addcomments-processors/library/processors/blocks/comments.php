<?php

class PoP_Module_Processor_CommentsBlocks extends PoP_Module_Processor_BlocksBase
{
    public final const MODULE_BLOCK_COMMENTS_SCROLL = 'block-comments-scroll';
    public final const MODULE_BLOCK_ADDCOMMENT = 'block-addcomment';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_COMMENTS_SCROLL],
            [self::class, self::MODULE_BLOCK_ADDCOMMENT],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_BLOCK_ADDCOMMENT => POP_ADDCOMMENTS_ROUTE_ADDCOMMENT,
            self::MODULE_BLOCK_COMMENTS_SCROLL => POP_BLOG_ROUTE_COMMENTS,
            default => parent::getRelevantRoute($module, $props),
        };
    }

    protected function getControlgroupTopSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_COMMENTS_SCROLL:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_COMMENTS];
        }

        return parent::getControlgroupTopSubmodule($module);
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_BLOCK_COMMENTS_SCROLL:
                $ret[] = [PoP_Module_Processor_CommentsDataloads::class, PoP_Module_Processor_CommentsDataloads::MODULE_DATALOAD_COMMENTS_SCROLL];
                break;

            case self::MODULE_BLOCK_ADDCOMMENT:
                $ret[] = [PoP_Module_Processor_CommentsDataloads::class, PoP_Module_Processor_CommentsDataloads::MODULE_DATALOAD_ADDCOMMENT];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_COMMENTS_SCROLL:
                $this->appendProp($module, $props, 'class', 'block-comments');
                break;
        }

        parent::initModelProps($module, $props);
    }
}



