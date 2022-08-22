<?php

class PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionBlocks extends GD_EM_Module_Processor_ScrollMapBlocksBase
{
    public final const COMPONENT_BLOCK_AUTHORFOLLOWERS_SCROLLMAP = 'block-authorfollowers-scrollmap';
    public final const COMPONENT_BLOCK_AUTHORFOLLOWINGUSERS_SCROLLMAP = 'block-authorfollowingusers-scrollmap';
    public final const COMPONENT_BLOCK_SINGLERECOMMENDEDBY_SCROLLMAP = 'block-singlerecommendedby-scrollmap';
    public final const COMPONENT_BLOCK_SINGLEUPVOTEDBY_SCROLLMAP = 'block-singleupvotedby-scrollmap';
    public final const COMPONENT_BLOCK_SINGLEDOWNVOTEDBY_SCROLLMAP = 'block-singledownvotedby-scrollmap';
    public final const COMPONENT_BLOCK_TAGSUBSCRIBERS_SCROLLMAP = 'block-tagsubscribers-scrollmap';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_BLOCK_AUTHORFOLLOWERS_SCROLLMAP,
            self::COMPONENT_BLOCK_AUTHORFOLLOWINGUSERS_SCROLLMAP,
            self::COMPONENT_BLOCK_SINGLERECOMMENDEDBY_SCROLLMAP,
            self::COMPONENT_BLOCK_SINGLEUPVOTEDBY_SCROLLMAP,
            self::COMPONENT_BLOCK_SINGLEDOWNVOTEDBY_SCROLLMAP,
            self::COMPONENT_BLOCK_TAGSUBSCRIBERS_SCROLLMAP,
        );
    }

    public function getRelevantRoute(\PoP\ComponentModel\Component\Component $component, array &$props): ?string
    {
        return match($component->name) {
            self::COMPONENT_BLOCK_AUTHORFOLLOWERS_SCROLLMAP => POP_SOCIALNETWORK_ROUTE_FOLLOWERS,
            self::COMPONENT_BLOCK_AUTHORFOLLOWINGUSERS_SCROLLMAP => POP_SOCIALNETWORK_ROUTE_FOLLOWINGUSERS,
            self::COMPONENT_BLOCK_SINGLEDOWNVOTEDBY_SCROLLMAP => POP_SOCIALNETWORK_ROUTE_DOWNVOTEDBY,
            self::COMPONENT_BLOCK_SINGLERECOMMENDEDBY_SCROLLMAP => POP_SOCIALNETWORK_ROUTE_RECOMMENDEDBY,
            self::COMPONENT_BLOCK_SINGLEUPVOTEDBY_SCROLLMAP => POP_SOCIALNETWORK_ROUTE_UPVOTEDBY,
            self::COMPONENT_BLOCK_TAGSUBSCRIBERS_SCROLLMAP => POP_SOCIALNETWORK_ROUTE_SUBSCRIBERS,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inner_components = array(
            self::COMPONENT_BLOCK_AUTHORFOLLOWERS_SCROLLMAP => [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_AUTHORFOLLOWERS_SCROLLMAP],
            self::COMPONENT_BLOCK_AUTHORFOLLOWINGUSERS_SCROLLMAP => [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLLMAP],
            self::COMPONENT_BLOCK_SINGLERECOMMENDEDBY_SCROLLMAP => [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_SINGLERECOMMENDEDBY_SCROLLMAP],
            self::COMPONENT_BLOCK_SINGLEUPVOTEDBY_SCROLLMAP => [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_SINGLEUPVOTEDBY_SCROLLMAP],
            self::COMPONENT_BLOCK_SINGLEDOWNVOTEDBY_SCROLLMAP => [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_SINGLEDOWNVOTEDBY_SCROLLMAP],
            self::COMPONENT_BLOCK_TAGSUBSCRIBERS_SCROLLMAP => [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_TAGSUBSCRIBERS_SCROLLMAP],
        );

        return $inner_components[$component->name] ?? null;
    }

    public function getTitle(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_BLOCK_AUTHORFOLLOWERS_SCROLLMAP:
            case self::COMPONENT_BLOCK_AUTHORFOLLOWINGUSERS_SCROLLMAP:
                return PoP_Module_Processor_CustomSectionBlocksUtils::getAuthorTitle();

            case self::COMPONENT_BLOCK_TAGSUBSCRIBERS_SCROLLMAP:
                return PoP_Module_Processor_CustomSectionBlocksUtils::getTagTitle();

            case self::COMPONENT_BLOCK_SINGLERECOMMENDEDBY_SCROLLMAP:
            case self::COMPONENT_BLOCK_SINGLEUPVOTEDBY_SCROLLMAP:
            case self::COMPONENT_BLOCK_SINGLEDOWNVOTEDBY_SCROLLMAP:
                return PoP_Module_Processor_CustomSectionBlocksUtils::getSingleTitle();
        }

        return parent::getTitle($component, $props);
    }

    protected function getControlgroupTopSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_BLOCK_AUTHORFOLLOWERS_SCROLLMAP:
            case self::COMPONENT_BLOCK_AUTHORFOLLOWINGUSERS_SCROLLMAP:
            case self::COMPONENT_BLOCK_SINGLERECOMMENDEDBY_SCROLLMAP:
            case self::COMPONENT_BLOCK_SINGLEUPVOTEDBY_SCROLLMAP:
            case self::COMPONENT_BLOCK_SINGLEDOWNVOTEDBY_SCROLLMAP:
            case self::COMPONENT_BLOCK_TAGSUBSCRIBERS_SCROLLMAP:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_BLOCKUSERLIST];
        }

        return parent::getControlgroupTopSubcomponent($component);
    }
}



