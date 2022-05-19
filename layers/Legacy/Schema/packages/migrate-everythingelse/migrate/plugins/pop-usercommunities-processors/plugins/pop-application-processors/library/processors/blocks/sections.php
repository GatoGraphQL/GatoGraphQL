<?php

class PoP_UserCommunities_Module_Processor_CustomSectionBlocks extends PoP_Module_Processor_SectionBlocksBase
{
    public final const COMPONENT_BLOCK_COMMUNITIES_SCROLL_DETAILS = 'block-communities-scroll-details';
    public final const COMPONENT_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLL_DETAILS = 'block-authormembers-scroll-details';
    public final const COMPONENT_BLOCK_COMMUNITIES_SCROLL_FULLVIEW = 'block-communities-scroll-fullview';
    public final const COMPONENT_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLL_FULLVIEW = 'block-authormembers-scroll-fullview';
    public final const COMPONENT_BLOCK_COMMUNITIES_SCROLL_THUMBNAIL = 'block-communities-scroll-thumbnail';
    public final const COMPONENT_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLL_THUMBNAIL = 'block-authormembers-scroll-thumbnail';
    public final const COMPONENT_BLOCK_COMMUNITIES_SCROLL_LIST = 'block-communities-scroll-list';
    public final const COMPONENT_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLL_LIST = 'block-authormembers-scroll-list';
    public final const COMPONENT_BLOCK_AUTHORCOMMUNITYMEMBERS_CAROUSEL = 'block-authormembers-carousel';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_COMMUNITIES_SCROLL_DETAILS],
            [self::class, self::COMPONENT_BLOCK_COMMUNITIES_SCROLL_FULLVIEW],
            [self::class, self::COMPONENT_BLOCK_COMMUNITIES_SCROLL_THUMBNAIL],
            [self::class, self::COMPONENT_BLOCK_COMMUNITIES_SCROLL_LIST],
            [self::class, self::COMPONENT_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLL_DETAILS],
            [self::class, self::COMPONENT_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLL_FULLVIEW],
            [self::class, self::COMPONENT_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLL_THUMBNAIL],
            [self::class, self::COMPONENT_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLL_LIST],
            [self::class, self::COMPONENT_BLOCK_AUTHORCOMMUNITYMEMBERS_CAROUSEL],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_BLOCK_AUTHORCOMMUNITYMEMBERS_CAROUSEL => POP_USERCOMMUNITIES_ROUTE_MEMBERS,
            self::COMPONENT_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLL_DETAILS => POP_USERCOMMUNITIES_ROUTE_MEMBERS,
            self::COMPONENT_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLL_FULLVIEW => POP_USERCOMMUNITIES_ROUTE_MEMBERS,
            self::COMPONENT_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLL_LIST => POP_USERCOMMUNITIES_ROUTE_MEMBERS,
            self::COMPONENT_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLL_THUMBNAIL => POP_USERCOMMUNITIES_ROUTE_MEMBERS,
            self::COMPONENT_BLOCK_COMMUNITIES_SCROLL_DETAILS => POP_USERCOMMUNITIES_ROUTE_COMMUNITIES,
            self::COMPONENT_BLOCK_COMMUNITIES_SCROLL_FULLVIEW => POP_USERCOMMUNITIES_ROUTE_COMMUNITIES,
            self::COMPONENT_BLOCK_COMMUNITIES_SCROLL_LIST => POP_USERCOMMUNITIES_ROUTE_COMMUNITIES,
            self::COMPONENT_BLOCK_COMMUNITIES_SCROLL_THUMBNAIL => POP_USERCOMMUNITIES_ROUTE_COMMUNITIES,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubcomponent(array $component)
    {
        $inner_components = array(
            self::COMPONENT_BLOCK_COMMUNITIES_SCROLL_DETAILS => [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_COMMUNITIES_SCROLL_DETAILS],
            self::COMPONENT_BLOCK_COMMUNITIES_SCROLL_FULLVIEW => [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_COMMUNITIES_SCROLL_FULLVIEW],
            self::COMPONENT_BLOCK_COMMUNITIES_SCROLL_THUMBNAIL => [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_COMMUNITIES_SCROLL_THUMBNAIL],
            self::COMPONENT_BLOCK_COMMUNITIES_SCROLL_LIST => [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_COMMUNITIES_SCROLL_LIST],
            self::COMPONENT_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLL_DETAILS => [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_DETAILS],
            self::COMPONENT_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLL_FULLVIEW => [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_FULLVIEW],
            self::COMPONENT_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLL_THUMBNAIL => [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_THUMBNAIL],
            self::COMPONENT_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLL_LIST => [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_LIST],
            self::COMPONENT_BLOCK_AUTHORCOMMUNITYMEMBERS_CAROUSEL => [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_CAROUSEL],
        );

        return $inner_components[$component[1]] ?? null;
    }

    protected function getControlgroupTopSubcomponent(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_COMMUNITIES_SCROLL_DETAILS:
            case self::COMPONENT_BLOCK_COMMUNITIES_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_COMMUNITIES_SCROLL_THUMBNAIL:
            case self::COMPONENT_BLOCK_COMMUNITIES_SCROLL_LIST:
            case self::COMPONENT_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLL_DETAILS:
            case self::COMPONENT_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLL_THUMBNAIL:
            case self::COMPONENT_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLL_LIST:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_BLOCKUSERLIST];
        }

        return parent::getControlgroupTopSubcomponent($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_AUTHORCOMMUNITYMEMBERS_CAROUSEL:
                // // Artificial property added to identify the template when adding component-resources
                // $this->setProp($component, $props, 'resourceloader', 'block-carousel');
                $this->appendProp($component, $props, 'class', 'pop-block-carousel block-users-carousel');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



