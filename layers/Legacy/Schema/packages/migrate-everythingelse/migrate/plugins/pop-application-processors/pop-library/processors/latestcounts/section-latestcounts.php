<?php

class PoPThemeWassup_Module_Processor_SectionLatestCounts extends PoP_Module_Processor_SectionLatestCountsBase
{
    public final const MODULE_LATESTCOUNT_POSTS = 'latestcount-posts';
    public final const MODULE_LATESTCOUNT_AUTHOR_POSTS = 'latestcount-author-posts';
    public final const MODULE_LATESTCOUNT_TAG_POSTS = 'latestcount-tag-posts';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LATESTCOUNT_POSTS],
            [self::class, self::MODULE_LATESTCOUNT_AUTHOR_POSTS],
            [self::class, self::MODULE_LATESTCOUNT_TAG_POSTS],
        );
    }

    public function getSectionClasses(array $module, array &$props)
    {
        $ret = parent::getSectionClasses($module, $props);

        switch ($module[1]) {
            case self::MODULE_LATESTCOUNT_POSTS:
            case self::MODULE_LATESTCOUNT_AUTHOR_POSTS:
            case self::MODULE_LATESTCOUNT_TAG_POSTS:
                foreach (gdDataloadAllcontentCategories() as $cat) {
                    $ret[] = 'post-'.$cat;
                }
                break;
        }
    
        return $ret;
    }

    public function isAuthor(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_LATESTCOUNT_AUTHOR_POSTS:
                return true;
        }
    
        return parent::isAuthor($module, $props);
    }

    public function isTag(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_LATESTCOUNT_TAG_POSTS:
                return true;
        }
    
        return parent::isTag($module, $props);
    }
}


