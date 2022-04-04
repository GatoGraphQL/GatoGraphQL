<?php

class PoP_Module_Processor_FunctionsContents extends PoP_Module_Processor_ContentsBase
{
    public final const MODULE_CONTENT_FOLLOWSUSERS = 'content-followsusers';
    public final const MODULE_CONTENT_UNFOLLOWSUSERS = 'content-unfollowsusers';
    public final const MODULE_CONTENT_RECOMMENDSPOSTS = 'content-recommendsposts';
    public final const MODULE_CONTENT_UNRECOMMENDSPOSTS = 'content-unrecommendsposts';
    public final const MODULE_CONTENT_SUBSCRIBESTOTAGS = 'content-subscribestotags';
    public final const MODULE_CONTENT_UNSUBSCRIBESFROMTAGS = 'content-unsubscribesfromtags';
    public final const MODULE_CONTENT_UPVOTESPOSTS = 'content-upvotesposts';
    public final const MODULE_CONTENT_UNDOUPVOTESPOSTS = 'content-undoupvotesposts';
    public final const MODULE_CONTENT_DOWNVOTESPOSTS = 'content-downvotesposts';
    public final const MODULE_CONTENT_UNDODOWNVOTESPOSTS = 'content-undodownvotesposts';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CONTENT_FOLLOWSUSERS],
            [self::class, self::MODULE_CONTENT_UNFOLLOWSUSERS],
            [self::class, self::MODULE_CONTENT_RECOMMENDSPOSTS],
            [self::class, self::MODULE_CONTENT_UNRECOMMENDSPOSTS],
            [self::class, self::MODULE_CONTENT_SUBSCRIBESTOTAGS],
            [self::class, self::MODULE_CONTENT_UNSUBSCRIBESFROMTAGS],
            [self::class, self::MODULE_CONTENT_UPVOTESPOSTS],
            [self::class, self::MODULE_CONTENT_UNDOUPVOTESPOSTS],
            [self::class, self::MODULE_CONTENT_DOWNVOTESPOSTS],
            [self::class, self::MODULE_CONTENT_UNDODOWNVOTESPOSTS],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_CONTENT_FOLLOWSUSERS => [PoP_Module_Processor_FunctionsContentMultipleInners::class, PoP_Module_Processor_FunctionsContentMultipleInners::MODULE_CONTENTINNER_FOLLOWSUSERS],
            self::MODULE_CONTENT_UNFOLLOWSUSERS => [PoP_Module_Processor_FunctionsContentMultipleInners::class, PoP_Module_Processor_FunctionsContentMultipleInners::MODULE_CONTENTINNER_UNFOLLOWSUSERS],
            self::MODULE_CONTENT_RECOMMENDSPOSTS => [PoP_Module_Processor_FunctionsContentMultipleInners::class, PoP_Module_Processor_FunctionsContentMultipleInners::MODULE_CONTENTINNER_RECOMMENDSPOSTS],
            self::MODULE_CONTENT_UNRECOMMENDSPOSTS => [PoP_Module_Processor_FunctionsContentMultipleInners::class, PoP_Module_Processor_FunctionsContentMultipleInners::MODULE_CONTENTINNER_UNRECOMMENDSPOSTS],
            self::MODULE_CONTENT_SUBSCRIBESTOTAGS => [PoP_Module_Processor_FunctionsContentMultipleInners::class, PoP_Module_Processor_FunctionsContentMultipleInners::MODULE_CONTENTINNER_SUBSCRIBESTOTAGS],
            self::MODULE_CONTENT_UNSUBSCRIBESFROMTAGS => [PoP_Module_Processor_FunctionsContentMultipleInners::class, PoP_Module_Processor_FunctionsContentMultipleInners::MODULE_CONTENTINNER_UNSUBSCRIBESFROMTAGS],
            self::MODULE_CONTENT_UPVOTESPOSTS => [PoP_Module_Processor_FunctionsContentMultipleInners::class, PoP_Module_Processor_FunctionsContentMultipleInners::MODULE_CONTENTINNER_UPVOTESPOSTS],
            self::MODULE_CONTENT_UNDOUPVOTESPOSTS => [PoP_Module_Processor_FunctionsContentMultipleInners::class, PoP_Module_Processor_FunctionsContentMultipleInners::MODULE_CONTENTINNER_UNDOUPVOTESPOSTS],
            self::MODULE_CONTENT_DOWNVOTESPOSTS => [PoP_Module_Processor_FunctionsContentMultipleInners::class, PoP_Module_Processor_FunctionsContentMultipleInners::MODULE_CONTENTINNER_DOWNVOTESPOSTS],
            self::MODULE_CONTENT_UNDODOWNVOTESPOSTS => [PoP_Module_Processor_FunctionsContentMultipleInners::class, PoP_Module_Processor_FunctionsContentMultipleInners::MODULE_CONTENTINNER_UNDODOWNVOTESPOSTS],
        );
        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }
}


