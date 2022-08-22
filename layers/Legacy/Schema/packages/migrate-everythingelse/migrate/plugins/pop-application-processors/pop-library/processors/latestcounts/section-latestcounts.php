<?php

class PoPThemeWassup_Module_Processor_SectionLatestCounts extends PoP_Module_Processor_SectionLatestCountsBase
{
    public final const COMPONENT_LATESTCOUNT_POSTS = 'latestcount-posts';
    public final const COMPONENT_LATESTCOUNT_AUTHOR_POSTS = 'latestcount-author-posts';
    public final const COMPONENT_LATESTCOUNT_TAG_POSTS = 'latestcount-tag-posts';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LATESTCOUNT_POSTS,
            self::COMPONENT_LATESTCOUNT_AUTHOR_POSTS,
            self::COMPONENT_LATESTCOUNT_TAG_POSTS,
        );
    }

    public function getSectionClasses(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getSectionClasses($component, $props);

        switch ($component->name) {
            case self::COMPONENT_LATESTCOUNT_POSTS:
            case self::COMPONENT_LATESTCOUNT_AUTHOR_POSTS:
            case self::COMPONENT_LATESTCOUNT_TAG_POSTS:
                foreach (gdDataloadAllcontentCategories() as $cat) {
                    $ret[] = 'post-'.$cat;
                }
                break;
        }
    
        return $ret;
    }

    public function isAuthor(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_LATESTCOUNT_AUTHOR_POSTS:
                return true;
        }
    
        return parent::isAuthor($component, $props);
    }

    public function isTag(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_LATESTCOUNT_TAG_POSTS:
                return true;
        }
    
        return parent::isTag($component, $props);
    }
}


