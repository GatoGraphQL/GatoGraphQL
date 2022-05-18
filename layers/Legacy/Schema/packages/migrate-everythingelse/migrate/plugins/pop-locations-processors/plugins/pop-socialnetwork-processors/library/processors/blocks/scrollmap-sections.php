<?php

class PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionBlocks extends GD_EM_Module_Processor_ScrollMapBlocksBase
{
    public final const MODULE_BLOCK_AUTHORFOLLOWERS_SCROLLMAP = 'block-authorfollowers-scrollmap';
    public final const MODULE_BLOCK_AUTHORFOLLOWINGUSERS_SCROLLMAP = 'block-authorfollowingusers-scrollmap';
    public final const MODULE_BLOCK_SINGLERECOMMENDEDBY_SCROLLMAP = 'block-singlerecommendedby-scrollmap';
    public final const MODULE_BLOCK_SINGLEUPVOTEDBY_SCROLLMAP = 'block-singleupvotedby-scrollmap';
    public final const MODULE_BLOCK_SINGLEDOWNVOTEDBY_SCROLLMAP = 'block-singledownvotedby-scrollmap';
    public final const MODULE_BLOCK_TAGSUBSCRIBERS_SCROLLMAP = 'block-tagsubscribers-scrollmap';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_AUTHORFOLLOWERS_SCROLLMAP],
            [self::class, self::MODULE_BLOCK_AUTHORFOLLOWINGUSERS_SCROLLMAP],
            [self::class, self::MODULE_BLOCK_SINGLERECOMMENDEDBY_SCROLLMAP],
            [self::class, self::MODULE_BLOCK_SINGLEUPVOTEDBY_SCROLLMAP],
            [self::class, self::MODULE_BLOCK_SINGLEDOWNVOTEDBY_SCROLLMAP],
            [self::class, self::MODULE_BLOCK_TAGSUBSCRIBERS_SCROLLMAP],
        );
    }

    public function getRelevantRoute(array $componentVariation, array &$props): ?string
    {
        return match($componentVariation[1]) {
            self::MODULE_BLOCK_AUTHORFOLLOWERS_SCROLLMAP => POP_SOCIALNETWORK_ROUTE_FOLLOWERS,
            self::MODULE_BLOCK_AUTHORFOLLOWINGUSERS_SCROLLMAP => POP_SOCIALNETWORK_ROUTE_FOLLOWINGUSERS,
            self::MODULE_BLOCK_SINGLEDOWNVOTEDBY_SCROLLMAP => POP_SOCIALNETWORK_ROUTE_DOWNVOTEDBY,
            self::MODULE_BLOCK_SINGLERECOMMENDEDBY_SCROLLMAP => POP_SOCIALNETWORK_ROUTE_RECOMMENDEDBY,
            self::MODULE_BLOCK_SINGLEUPVOTEDBY_SCROLLMAP => POP_SOCIALNETWORK_ROUTE_UPVOTEDBY,
            self::MODULE_BLOCK_TAGSUBSCRIBERS_SCROLLMAP => POP_SOCIALNETWORK_ROUTE_SUBSCRIBERS,
            default => parent::getRelevantRoute($componentVariation, $props),
        };
    }

    protected function getInnerSubmodule(array $componentVariation)
    {
        $inner_modules = array(
            self::MODULE_BLOCK_AUTHORFOLLOWERS_SCROLLMAP => [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLLMAP],
            self::MODULE_BLOCK_AUTHORFOLLOWINGUSERS_SCROLLMAP => [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLLMAP],
            self::MODULE_BLOCK_SINGLERECOMMENDEDBY_SCROLLMAP => [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLLMAP],
            self::MODULE_BLOCK_SINGLEUPVOTEDBY_SCROLLMAP => [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLLMAP],
            self::MODULE_BLOCK_SINGLEDOWNVOTEDBY_SCROLLMAP => [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLLMAP],
            self::MODULE_BLOCK_TAGSUBSCRIBERS_SCROLLMAP => [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLLMAP],
        );

        return $inner_modules[$componentVariation[1]] ?? null;
    }

    public function getTitle(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_AUTHORFOLLOWERS_SCROLLMAP:
            case self::MODULE_BLOCK_AUTHORFOLLOWINGUSERS_SCROLLMAP:
                return PoP_Module_Processor_CustomSectionBlocksUtils::getAuthorTitle();

            case self::MODULE_BLOCK_TAGSUBSCRIBERS_SCROLLMAP:
                return PoP_Module_Processor_CustomSectionBlocksUtils::getTagTitle();

            case self::MODULE_BLOCK_SINGLERECOMMENDEDBY_SCROLLMAP:
            case self::MODULE_BLOCK_SINGLEUPVOTEDBY_SCROLLMAP:
            case self::MODULE_BLOCK_SINGLEDOWNVOTEDBY_SCROLLMAP:
                return PoP_Module_Processor_CustomSectionBlocksUtils::getSingleTitle();
        }

        return parent::getTitle($componentVariation, $props);
    }

    protected function getControlgroupTopSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_AUTHORFOLLOWERS_SCROLLMAP:
            case self::MODULE_BLOCK_AUTHORFOLLOWINGUSERS_SCROLLMAP:
            case self::MODULE_BLOCK_SINGLERECOMMENDEDBY_SCROLLMAP:
            case self::MODULE_BLOCK_SINGLEUPVOTEDBY_SCROLLMAP:
            case self::MODULE_BLOCK_SINGLEDOWNVOTEDBY_SCROLLMAP:
            case self::MODULE_BLOCK_TAGSUBSCRIBERS_SCROLLMAP:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_BLOCKUSERLIST];
        }

        return parent::getControlgroupTopSubmodule($componentVariation);
    }
}



