<?php

class PoP_UserCommunities_Module_Processor_CustomScrolls extends PoP_Module_Processor_ScrollsBase
{
    public final const COMPONENT_SCROLL_MYMEMBERS_FULLVIEWPREVIEW = 'scroll-mymembers-fullviewpreview';
    public final const COMPONENT_SCROLL_COMMUNITIES_DETAILS = 'scroll-communities-details';
    public final const COMPONENT_SCROLL_COMMUNITIES_FULLVIEW = 'scroll-communities-fullview';
    public final const COMPONENT_SCROLL_COMMUNITIES_THUMBNAIL = 'scroll-communities-thumbnail';
    public final const COMPONENT_SCROLL_COMMUNITIES_LIST = 'scroll-communities-list';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SCROLL_MYMEMBERS_FULLVIEWPREVIEW],
            [self::class, self::COMPONENT_SCROLL_COMMUNITIES_DETAILS],
            [self::class, self::COMPONENT_SCROLL_COMMUNITIES_FULLVIEW],
            [self::class, self::COMPONENT_SCROLL_COMMUNITIES_THUMBNAIL],
            [self::class, self::COMPONENT_SCROLL_COMMUNITIES_LIST],
        );
    }


    public function getInnerSubmodule(array $component)
    {
        $inners = array(
            self::COMPONENT_SCROLL_MYMEMBERS_FULLVIEWPREVIEW => [PoP_UserCommunities_Module_Processor_CustomScrollInners::class, PoP_UserCommunities_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_MYMEMBERS_FULLVIEWPREVIEW],
            self::COMPONENT_SCROLL_COMMUNITIES_DETAILS => [PoP_UserCommunities_Module_Processor_CustomScrollInners::class, PoP_UserCommunities_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_COMMUNITIES_DETAILS],
            self::COMPONENT_SCROLL_COMMUNITIES_FULLVIEW => [PoP_UserCommunities_Module_Processor_CustomScrollInners::class, PoP_UserCommunities_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_COMMUNITIES_FULLVIEW],
            self::COMPONENT_SCROLL_COMMUNITIES_THUMBNAIL => [PoP_UserCommunities_Module_Processor_CustomScrollInners::class, PoP_UserCommunities_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_COMMUNITIES_THUMBNAIL],
            self::COMPONENT_SCROLL_COMMUNITIES_LIST => [PoP_UserCommunities_Module_Processor_CustomScrollInners::class, PoP_UserCommunities_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_COMMUNITIES_LIST],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }

    public function initModelProps(array $component, array &$props): void
    {

        // Extra classes
        $thumbnails = array(
            [self::class, self::COMPONENT_SCROLL_COMMUNITIES_THUMBNAIL],
        );
        $lists = array(
            [self::class, self::COMPONENT_SCROLL_COMMUNITIES_LIST],
        );
        $details = array(
            [self::class, self::COMPONENT_SCROLL_COMMUNITIES_DETAILS],
        );
        $fullviews = array(
            [self::class, self::COMPONENT_SCROLL_MYMEMBERS_FULLVIEWPREVIEW],
            [self::class, self::COMPONENT_SCROLL_COMMUNITIES_FULLVIEW],
        );

        $extra_class = '';
        if (in_array($component, $fullviews)) {
            $extra_class = 'fullview';
        } elseif (in_array($component, $details)) {
            $extra_class = 'details';
        } elseif (in_array($component, $thumbnails)) {
            $extra_class = 'thumb';
        } elseif (in_array($component, $lists)) {
            $extra_class = 'list';
        }
        $this->appendProp($component, $props, 'class', $extra_class);

        parent::initModelProps($component, $props);
    }
}


