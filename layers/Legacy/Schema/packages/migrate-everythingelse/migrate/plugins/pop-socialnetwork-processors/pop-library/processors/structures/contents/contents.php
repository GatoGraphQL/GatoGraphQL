<?php

class PoP_Module_Processor_FunctionsContents extends PoP_Module_Processor_ContentsBase
{
    public final const COMPONENT_CONTENT_FOLLOWSUSERS = 'content-followsusers';
    public final const COMPONENT_CONTENT_UNFOLLOWSUSERS = 'content-unfollowsusers';
    public final const COMPONENT_CONTENT_RECOMMENDSPOSTS = 'content-recommendsposts';
    public final const COMPONENT_CONTENT_UNRECOMMENDSPOSTS = 'content-unrecommendsposts';
    public final const COMPONENT_CONTENT_SUBSCRIBESTOTAGS = 'content-subscribestotags';
    public final const COMPONENT_CONTENT_UNSUBSCRIBESFROMTAGS = 'content-unsubscribesfromtags';
    public final const COMPONENT_CONTENT_UPVOTESPOSTS = 'content-upvotesposts';
    public final const COMPONENT_CONTENT_UNDOUPVOTESPOSTS = 'content-undoupvotesposts';
    public final const COMPONENT_CONTENT_DOWNVOTESPOSTS = 'content-downvotesposts';
    public final const COMPONENT_CONTENT_UNDODOWNVOTESPOSTS = 'content-undodownvotesposts';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_CONTENT_FOLLOWSUSERS],
            [self::class, self::COMPONENT_CONTENT_UNFOLLOWSUSERS],
            [self::class, self::COMPONENT_CONTENT_RECOMMENDSPOSTS],
            [self::class, self::COMPONENT_CONTENT_UNRECOMMENDSPOSTS],
            [self::class, self::COMPONENT_CONTENT_SUBSCRIBESTOTAGS],
            [self::class, self::COMPONENT_CONTENT_UNSUBSCRIBESFROMTAGS],
            [self::class, self::COMPONENT_CONTENT_UPVOTESPOSTS],
            [self::class, self::COMPONENT_CONTENT_UNDOUPVOTESPOSTS],
            [self::class, self::COMPONENT_CONTENT_DOWNVOTESPOSTS],
            [self::class, self::COMPONENT_CONTENT_UNDODOWNVOTESPOSTS],
        );
    }

    public function getInnerSubcomponent(array $component)
    {
        $inners = array(
            self::COMPONENT_CONTENT_FOLLOWSUSERS => [PoP_Module_Processor_FunctionsContentMultipleInners::class, PoP_Module_Processor_FunctionsContentMultipleInners::COMPONENT_CONTENTINNER_FOLLOWSUSERS],
            self::COMPONENT_CONTENT_UNFOLLOWSUSERS => [PoP_Module_Processor_FunctionsContentMultipleInners::class, PoP_Module_Processor_FunctionsContentMultipleInners::COMPONENT_CONTENTINNER_UNFOLLOWSUSERS],
            self::COMPONENT_CONTENT_RECOMMENDSPOSTS => [PoP_Module_Processor_FunctionsContentMultipleInners::class, PoP_Module_Processor_FunctionsContentMultipleInners::COMPONENT_CONTENTINNER_RECOMMENDSPOSTS],
            self::COMPONENT_CONTENT_UNRECOMMENDSPOSTS => [PoP_Module_Processor_FunctionsContentMultipleInners::class, PoP_Module_Processor_FunctionsContentMultipleInners::COMPONENT_CONTENTINNER_UNRECOMMENDSPOSTS],
            self::COMPONENT_CONTENT_SUBSCRIBESTOTAGS => [PoP_Module_Processor_FunctionsContentMultipleInners::class, PoP_Module_Processor_FunctionsContentMultipleInners::COMPONENT_CONTENTINNER_SUBSCRIBESTOTAGS],
            self::COMPONENT_CONTENT_UNSUBSCRIBESFROMTAGS => [PoP_Module_Processor_FunctionsContentMultipleInners::class, PoP_Module_Processor_FunctionsContentMultipleInners::COMPONENT_CONTENTINNER_UNSUBSCRIBESFROMTAGS],
            self::COMPONENT_CONTENT_UPVOTESPOSTS => [PoP_Module_Processor_FunctionsContentMultipleInners::class, PoP_Module_Processor_FunctionsContentMultipleInners::COMPONENT_CONTENTINNER_UPVOTESPOSTS],
            self::COMPONENT_CONTENT_UNDOUPVOTESPOSTS => [PoP_Module_Processor_FunctionsContentMultipleInners::class, PoP_Module_Processor_FunctionsContentMultipleInners::COMPONENT_CONTENTINNER_UNDOUPVOTESPOSTS],
            self::COMPONENT_CONTENT_DOWNVOTESPOSTS => [PoP_Module_Processor_FunctionsContentMultipleInners::class, PoP_Module_Processor_FunctionsContentMultipleInners::COMPONENT_CONTENTINNER_DOWNVOTESPOSTS],
            self::COMPONENT_CONTENT_UNDODOWNVOTESPOSTS => [PoP_Module_Processor_FunctionsContentMultipleInners::class, PoP_Module_Processor_FunctionsContentMultipleInners::COMPONENT_CONTENTINNER_UNDODOWNVOTESPOSTS],
        );
        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }
}


