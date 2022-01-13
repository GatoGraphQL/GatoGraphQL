<?php

class PoPThemeWassup_CommonPages_EM_Module_Processor_SectionLatestCounts extends PoP_Module_Processor_SectionLatestCountsBase
{
    public const MODULE_LATESTCOUNT_LOCATIONPOSTS = 'latestcount-locationposts';
    public const MODULE_LATESTCOUNT_AUTHOR_LOCATIONPOSTS = 'latestcount-author-locationposts';
    public const MODULE_LATESTCOUNT_TAG_LOCATIONPOSTS = 'latestcount-tag-locationposts';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LATESTCOUNT_LOCATIONPOSTS],
            [self::class, self::MODULE_LATESTCOUNT_AUTHOR_LOCATIONPOSTS],
            [self::class, self::MODULE_LATESTCOUNT_TAG_LOCATIONPOSTS],
        );
    }

    public function getObjectName(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_LATESTCOUNT_LOCATIONPOSTS:
            case self::MODULE_LATESTCOUNT_AUTHOR_LOCATIONPOSTS:
            case self::MODULE_LATESTCOUNT_TAG_LOCATIONPOSTS:
                return PoP_LocationPosts_PostNameUtils::getNameLc();
        }
    
        return parent::getObjectNames($module, $props);
    }

    public function getObjectNames(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_LATESTCOUNT_LOCATIONPOSTS:
            case self::MODULE_LATESTCOUNT_AUTHOR_LOCATIONPOSTS:
            case self::MODULE_LATESTCOUNT_TAG_LOCATIONPOSTS:
                return PoP_LocationPosts_PostNameUtils::getNamesLc();
        }
    
        return parent::getObjectNames($module, $props);
    }

    public function getSectionClasses(array $module, array &$props)
    {
        $ret = parent::getSectionClasses($module, $props);

        switch ($module[1]) {
            case self::MODULE_LATESTCOUNT_LOCATIONPOSTS:
            case self::MODULE_LATESTCOUNT_AUTHOR_LOCATIONPOSTS:
            case self::MODULE_LATESTCOUNT_TAG_LOCATIONPOSTS:
                $ret[] = POP_LOCATIONPOSTS_POSTTYPE_LOCATIONPOST.'-'.POP_LOCATIONPOSTS_CAT_ALL;
                break;
        }

        // Allow to hook in POP_CONTENTPOSTLINKS_CAT_CONTENTPOSTLINKS
        $ret = \PoP\Root\App::getHookManager()->applyFilters(
            'latestcounts:locationposts:classes',
            $ret,
            $module,
            $props
        );
    
        return $ret;
    }

    public function isAuthor(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_LATESTCOUNT_AUTHOR_LOCATIONPOSTS:
                return true;
        }
    
        return parent::isAuthor($module, $props);
    }

    public function isTag(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_LATESTCOUNT_TAG_LOCATIONPOSTS:
                return true;
        }
    
        return parent::isTag($module, $props);
    }
}


