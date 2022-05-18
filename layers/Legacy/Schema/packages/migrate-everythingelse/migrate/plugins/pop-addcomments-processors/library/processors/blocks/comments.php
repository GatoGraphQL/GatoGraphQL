<?php

class PoP_Module_Processor_CommentsBlocks extends PoP_Module_Processor_BlocksBase
{
    public final const MODULE_BLOCK_COMMENTS_SCROLL = 'block-comments-scroll';
    public final const MODULE_BLOCK_ADDCOMMENT = 'block-addcomment';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_COMMENTS_SCROLL],
            [self::class, self::MODULE_BLOCK_ADDCOMMENT],
        );
    }

    public function getRelevantRoute(array $componentVariation, array &$props): ?string
    {
        return match($componentVariation[1]) {
            self::MODULE_BLOCK_ADDCOMMENT => POP_ADDCOMMENTS_ROUTE_ADDCOMMENT,
            self::MODULE_BLOCK_COMMENTS_SCROLL => POP_BLOG_ROUTE_COMMENTS,
            default => parent::getRelevantRoute($componentVariation, $props),
        };
    }

    protected function getControlgroupTopSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_COMMENTS_SCROLL:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_COMMENTS];
        }

        return parent::getControlgroupTopSubmodule($componentVariation);
    }

    protected function getInnerSubmodules(array $componentVariation): array
    {
        $ret = parent::getInnerSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_COMMENTS_SCROLL:
                $ret[] = [PoP_Module_Processor_CommentsDataloads::class, PoP_Module_Processor_CommentsDataloads::MODULE_DATALOAD_COMMENTS_SCROLL];
                break;

            case self::MODULE_BLOCK_ADDCOMMENT:
                $ret[] = [PoP_Module_Processor_CommentsDataloads::class, PoP_Module_Processor_CommentsDataloads::MODULE_DATALOAD_ADDCOMMENT];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_COMMENTS_SCROLL:
                $this->appendProp($componentVariation, $props, 'class', 'block-comments');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



