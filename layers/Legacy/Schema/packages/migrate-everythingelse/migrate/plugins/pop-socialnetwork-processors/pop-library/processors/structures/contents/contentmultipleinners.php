<?php

class PoP_Module_Processor_FunctionsContentMultipleInners extends PoP_Module_Processor_ContentMultipleInnersBase
{
    public final const COMPONENT_CONTENTINNER_FOLLOWSUSERS = 'contentinner-followsusers';
    public final const COMPONENT_CONTENTINNER_UNFOLLOWSUSERS = 'contentinner-unfollowsusers';
    public final const COMPONENT_CONTENTINNER_RECOMMENDSPOSTS = 'contentinner-recommendsposts';
    public final const COMPONENT_CONTENTINNER_UNRECOMMENDSPOSTS = 'contentinner-unrecommendsposts';
    public final const COMPONENT_CONTENTINNER_SUBSCRIBESTOTAGS = 'contentinner-subscribestotags';
    public final const COMPONENT_CONTENTINNER_UNSUBSCRIBESFROMTAGS = 'contentinner-unsubscribesfromtags';
    public final const COMPONENT_CONTENTINNER_UPVOTESPOSTS = 'contentinner-upvotesposts';
    public final const COMPONENT_CONTENTINNER_UNDOUPVOTESPOSTS = 'contentinner-undoupvotesposts';
    public final const COMPONENT_CONTENTINNER_DOWNVOTESPOSTS = 'contentinner-downvotesposts';
    public final const COMPONENT_CONTENTINNER_UNDODOWNVOTESPOSTS = 'contentinner-undodownvotesposts';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_CONTENTINNER_FOLLOWSUSERS],
            [self::class, self::COMPONENT_CONTENTINNER_UNFOLLOWSUSERS],
            [self::class, self::COMPONENT_CONTENTINNER_RECOMMENDSPOSTS],
            [self::class, self::COMPONENT_CONTENTINNER_UNRECOMMENDSPOSTS],
            [self::class, self::COMPONENT_CONTENTINNER_SUBSCRIBESTOTAGS],
            [self::class, self::COMPONENT_CONTENTINNER_UNSUBSCRIBESFROMTAGS],
            [self::class, self::COMPONENT_CONTENTINNER_UPVOTESPOSTS],
            [self::class, self::COMPONENT_CONTENTINNER_UNDOUPVOTESPOSTS],
            [self::class, self::COMPONENT_CONTENTINNER_DOWNVOTESPOSTS],
            [self::class, self::COMPONENT_CONTENTINNER_UNDODOWNVOTESPOSTS],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        switch ($component[1]) {
         // When up-voting (and similar for down-voting), it must also do an undo down-vote had the post been down-voted
            case self::COMPONENT_CONTENTINNER_UPVOTESPOSTS:
                $ret[] = [PoP_Module_Processor_ShowHideElemMultiStyleLayouts::class, PoP_Module_Processor_ShowHideElemMultiStyleLayouts::COMPONENT_LAYOUT_UPVOTEPOST_STYLES];
                $ret[] = [PoP_Module_Processor_ShowHideElemMultiStyleLayouts::class, PoP_Module_Processor_ShowHideElemMultiStyleLayouts::COMPONENT_LAYOUT_UNDODOWNVOTEPOST_STYLES];
                break;

            case self::COMPONENT_CONTENTINNER_DOWNVOTESPOSTS:
                $ret[] = [PoP_Module_Processor_ShowHideElemMultiStyleLayouts::class, PoP_Module_Processor_ShowHideElemMultiStyleLayouts::COMPONENT_LAYOUT_DOWNVOTEPOST_STYLES];
                $ret[] = [PoP_Module_Processor_ShowHideElemMultiStyleLayouts::class, PoP_Module_Processor_ShowHideElemMultiStyleLayouts::COMPONENT_LAYOUT_UNDOUPVOTEPOST_STYLES];
                break;

            default:
                $layouts = array(
                    self::COMPONENT_CONTENTINNER_FOLLOWSUSERS => [PoP_Module_Processor_ShowHideElemMultiStyleLayouts::class, PoP_Module_Processor_ShowHideElemMultiStyleLayouts::COMPONENT_LAYOUT_FOLLOWUSER_STYLES],
                    self::COMPONENT_CONTENTINNER_UNFOLLOWSUSERS => [PoP_Module_Processor_ShowHideElemMultiStyleLayouts::class, PoP_Module_Processor_ShowHideElemMultiStyleLayouts::COMPONENT_LAYOUT_UNFOLLOWUSER_STYLES],
                    self::COMPONENT_CONTENTINNER_RECOMMENDSPOSTS => [PoP_Module_Processor_ShowHideElemMultiStyleLayouts::class, PoP_Module_Processor_ShowHideElemMultiStyleLayouts::COMPONENT_LAYOUT_RECOMMENDPOST_STYLES],
                    self::COMPONENT_CONTENTINNER_UNRECOMMENDSPOSTS => [PoP_Module_Processor_ShowHideElemMultiStyleLayouts::class, PoP_Module_Processor_ShowHideElemMultiStyleLayouts::COMPONENT_LAYOUT_UNRECOMMENDPOST_STYLES],
                    self::COMPONENT_CONTENTINNER_SUBSCRIBESTOTAGS => [PoP_Module_Processor_ShowHideElemMultiStyleLayouts::class, PoP_Module_Processor_ShowHideElemMultiStyleLayouts::COMPONENT_LAYOUT_SUBSCRIBETOTAG_STYLES],
                    self::COMPONENT_CONTENTINNER_UNSUBSCRIBESFROMTAGS => [PoP_Module_Processor_ShowHideElemMultiStyleLayouts::class, PoP_Module_Processor_ShowHideElemMultiStyleLayouts::COMPONENT_LAYOUT_UNSUBSCRIBEFROMTAG_STYLES],
                    self::COMPONENT_CONTENTINNER_UNDOUPVOTESPOSTS => [PoP_Module_Processor_ShowHideElemMultiStyleLayouts::class, PoP_Module_Processor_ShowHideElemMultiStyleLayouts::COMPONENT_LAYOUT_UNDOUPVOTEPOST_STYLES],
                    self::COMPONENT_CONTENTINNER_UNDODOWNVOTESPOSTS => [PoP_Module_Processor_ShowHideElemMultiStyleLayouts::class, PoP_Module_Processor_ShowHideElemMultiStyleLayouts::COMPONENT_LAYOUT_UNDODOWNVOTEPOST_STYLES],
                    // self::COMPONENT_CONTENTINNER_UPVOTESPOSTS => [PoP_Module_Processor_ShowHideElemMultiStyleLayouts::class, PoP_Module_Processor_ShowHideElemMultiStyleLayouts::COMPONENT_LAYOUT_UPVOTEPOST_STYLES],
                    // self::COMPONENT_CONTENTINNER_DOWNVOTESPOSTS => [PoP_Module_Processor_ShowHideElemMultiStyleLayouts::class, PoP_Module_Processor_ShowHideElemMultiStyleLayouts::COMPONENT_LAYOUT_DOWNVOTEPOST_STYLES],
                );
                if ($layout = $layouts[$component[1]] ?? null) {
                    $ret[] = $layout;
                }
                break;
        }

        return $ret;
    }
}


