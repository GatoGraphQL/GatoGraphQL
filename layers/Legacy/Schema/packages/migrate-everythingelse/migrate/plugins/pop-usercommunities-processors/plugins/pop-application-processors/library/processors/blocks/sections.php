<?php

class PoP_UserCommunities_Module_Processor_CustomSectionBlocks extends PoP_Module_Processor_SectionBlocksBase
{
    public final const MODULE_BLOCK_COMMUNITIES_SCROLL_DETAILS = 'block-communities-scroll-details';
    public final const MODULE_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLL_DETAILS = 'block-authormembers-scroll-details';
    public final const MODULE_BLOCK_COMMUNITIES_SCROLL_FULLVIEW = 'block-communities-scroll-fullview';
    public final const MODULE_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLL_FULLVIEW = 'block-authormembers-scroll-fullview';
    public final const MODULE_BLOCK_COMMUNITIES_SCROLL_THUMBNAIL = 'block-communities-scroll-thumbnail';
    public final const MODULE_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLL_THUMBNAIL = 'block-authormembers-scroll-thumbnail';
    public final const MODULE_BLOCK_COMMUNITIES_SCROLL_LIST = 'block-communities-scroll-list';
    public final const MODULE_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLL_LIST = 'block-authormembers-scroll-list';
    public final const MODULE_BLOCK_AUTHORCOMMUNITYMEMBERS_CAROUSEL = 'block-authormembers-carousel';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_COMMUNITIES_SCROLL_DETAILS],
            [self::class, self::MODULE_BLOCK_COMMUNITIES_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_COMMUNITIES_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_COMMUNITIES_SCROLL_LIST],
            [self::class, self::MODULE_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLL_DETAILS],
            [self::class, self::MODULE_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLL_LIST],
            [self::class, self::MODULE_BLOCK_AUTHORCOMMUNITYMEMBERS_CAROUSEL],
        );
    }

    public function getRelevantRoute(array $componentVariation, array &$props): ?string
    {
        return match($componentVariation[1]) {
            self::MODULE_BLOCK_AUTHORCOMMUNITYMEMBERS_CAROUSEL => POP_USERCOMMUNITIES_ROUTE_MEMBERS,
            self::MODULE_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLL_DETAILS => POP_USERCOMMUNITIES_ROUTE_MEMBERS,
            self::MODULE_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLL_FULLVIEW => POP_USERCOMMUNITIES_ROUTE_MEMBERS,
            self::MODULE_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLL_LIST => POP_USERCOMMUNITIES_ROUTE_MEMBERS,
            self::MODULE_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLL_THUMBNAIL => POP_USERCOMMUNITIES_ROUTE_MEMBERS,
            self::MODULE_BLOCK_COMMUNITIES_SCROLL_DETAILS => POP_USERCOMMUNITIES_ROUTE_COMMUNITIES,
            self::MODULE_BLOCK_COMMUNITIES_SCROLL_FULLVIEW => POP_USERCOMMUNITIES_ROUTE_COMMUNITIES,
            self::MODULE_BLOCK_COMMUNITIES_SCROLL_LIST => POP_USERCOMMUNITIES_ROUTE_COMMUNITIES,
            self::MODULE_BLOCK_COMMUNITIES_SCROLL_THUMBNAIL => POP_USERCOMMUNITIES_ROUTE_COMMUNITIES,
            default => parent::getRelevantRoute($componentVariation, $props),
        };
    }

    protected function getInnerSubmodule(array $componentVariation)
    {
        $inner_modules = array(
            self::MODULE_BLOCK_COMMUNITIES_SCROLL_DETAILS => [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_COMMUNITIES_SCROLL_DETAILS],
            self::MODULE_BLOCK_COMMUNITIES_SCROLL_FULLVIEW => [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_COMMUNITIES_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_COMMUNITIES_SCROLL_THUMBNAIL => [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_COMMUNITIES_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_COMMUNITIES_SCROLL_LIST => [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_COMMUNITIES_SCROLL_LIST],
            self::MODULE_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLL_DETAILS => [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_DETAILS],
            self::MODULE_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLL_FULLVIEW => [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLL_THUMBNAIL => [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLL_LIST => [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_LIST],
            self::MODULE_BLOCK_AUTHORCOMMUNITYMEMBERS_CAROUSEL => [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_CAROUSEL],
        );

        return $inner_modules[$componentVariation[1]] ?? null;
    }

    protected function getControlgroupTopSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_COMMUNITIES_SCROLL_DETAILS:
            case self::MODULE_BLOCK_COMMUNITIES_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_COMMUNITIES_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_COMMUNITIES_SCROLL_LIST:
            case self::MODULE_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLL_DETAILS:
            case self::MODULE_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLL_LIST:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_BLOCKUSERLIST];
        }

        return parent::getControlgroupTopSubmodule($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_AUTHORCOMMUNITYMEMBERS_CAROUSEL:
                // // Artificial property added to identify the template when adding module-resources
                // $this->setProp($componentVariation, $props, 'resourceloader', 'block-carousel');
                $this->appendProp($componentVariation, $props, 'class', 'pop-block-carousel block-users-carousel');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



