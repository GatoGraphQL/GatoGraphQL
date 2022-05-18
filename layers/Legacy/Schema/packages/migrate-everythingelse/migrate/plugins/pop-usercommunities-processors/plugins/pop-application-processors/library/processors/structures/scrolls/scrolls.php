<?php

class PoP_UserCommunities_Module_Processor_CustomScrolls extends PoP_Module_Processor_ScrollsBase
{
    public final const MODULE_SCROLL_MYMEMBERS_FULLVIEWPREVIEW = 'scroll-mymembers-fullviewpreview';
    public final const MODULE_SCROLL_COMMUNITIES_DETAILS = 'scroll-communities-details';
    public final const MODULE_SCROLL_COMMUNITIES_FULLVIEW = 'scroll-communities-fullview';
    public final const MODULE_SCROLL_COMMUNITIES_THUMBNAIL = 'scroll-communities-thumbnail';
    public final const MODULE_SCROLL_COMMUNITIES_LIST = 'scroll-communities-list';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLL_MYMEMBERS_FULLVIEWPREVIEW],
            [self::class, self::MODULE_SCROLL_COMMUNITIES_DETAILS],
            [self::class, self::MODULE_SCROLL_COMMUNITIES_FULLVIEW],
            [self::class, self::MODULE_SCROLL_COMMUNITIES_THUMBNAIL],
            [self::class, self::MODULE_SCROLL_COMMUNITIES_LIST],
        );
    }


    public function getInnerSubmodule(array $componentVariation)
    {
        $inners = array(
            self::MODULE_SCROLL_MYMEMBERS_FULLVIEWPREVIEW => [PoP_UserCommunities_Module_Processor_CustomScrollInners::class, PoP_UserCommunities_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_MYMEMBERS_FULLVIEWPREVIEW],
            self::MODULE_SCROLL_COMMUNITIES_DETAILS => [PoP_UserCommunities_Module_Processor_CustomScrollInners::class, PoP_UserCommunities_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_COMMUNITIES_DETAILS],
            self::MODULE_SCROLL_COMMUNITIES_FULLVIEW => [PoP_UserCommunities_Module_Processor_CustomScrollInners::class, PoP_UserCommunities_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_COMMUNITIES_FULLVIEW],
            self::MODULE_SCROLL_COMMUNITIES_THUMBNAIL => [PoP_UserCommunities_Module_Processor_CustomScrollInners::class, PoP_UserCommunities_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_COMMUNITIES_THUMBNAIL],
            self::MODULE_SCROLL_COMMUNITIES_LIST => [PoP_UserCommunities_Module_Processor_CustomScrollInners::class, PoP_UserCommunities_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_COMMUNITIES_LIST],
        );

        if ($inner = $inners[$componentVariation[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {

        // Extra classes
        $thumbnails = array(
            [self::class, self::MODULE_SCROLL_COMMUNITIES_THUMBNAIL],
        );
        $lists = array(
            [self::class, self::MODULE_SCROLL_COMMUNITIES_LIST],
        );
        $details = array(
            [self::class, self::MODULE_SCROLL_COMMUNITIES_DETAILS],
        );
        $fullviews = array(
            [self::class, self::MODULE_SCROLL_MYMEMBERS_FULLVIEWPREVIEW],
            [self::class, self::MODULE_SCROLL_COMMUNITIES_FULLVIEW],
        );

        $extra_class = '';
        if (in_array($componentVariation, $fullviews)) {
            $extra_class = 'fullview';
        } elseif (in_array($componentVariation, $details)) {
            $extra_class = 'details';
        } elseif (in_array($componentVariation, $thumbnails)) {
            $extra_class = 'thumb';
        } elseif (in_array($componentVariation, $lists)) {
            $extra_class = 'list';
        }
        $this->appendProp($componentVariation, $props, 'class', $extra_class);

        parent::initModelProps($componentVariation, $props);
    }
}


