<?php

class PoP_Module_Processor_CommentsBlocks extends PoP_Module_Processor_BlocksBase
{
    public final const COMPONENT_BLOCK_COMMENTS_SCROLL = 'block-comments-scroll';
    public final const COMPONENT_BLOCK_ADDCOMMENT = 'block-addcomment';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_BLOCK_COMMENTS_SCROLL,
            self::COMPONENT_BLOCK_ADDCOMMENT,
        );
    }

    public function getRelevantRoute(\PoP\ComponentModel\Component\Component $component, array &$props): ?string
    {
        return match($component->name) {
            self::COMPONENT_BLOCK_ADDCOMMENT => POP_ADDCOMMENTS_ROUTE_ADDCOMMENT,
            self::COMPONENT_BLOCK_COMMENTS_SCROLL => POP_BLOG_ROUTE_COMMENTS,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getControlgroupTopSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_BLOCK_COMMENTS_SCROLL:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_COMMENTS];
        }

        return parent::getControlgroupTopSubcomponent($component);
    }

    protected function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_BLOCK_COMMENTS_SCROLL:
                $ret[] = [PoP_Module_Processor_CommentsDataloads::class, PoP_Module_Processor_CommentsDataloads::COMPONENT_DATALOAD_COMMENTS_SCROLL];
                break;

            case self::COMPONENT_BLOCK_ADDCOMMENT:
                $ret[] = [PoP_Module_Processor_CommentsDataloads::class, PoP_Module_Processor_CommentsDataloads::COMPONENT_DATALOAD_ADDCOMMENT];
                break;
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_BLOCK_COMMENTS_SCROLL:
                $this->appendProp($component, $props, 'class', 'block-comments');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



