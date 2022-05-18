<?php

class PoPThemeWassup_CommonPages_EM_Module_Processor_SectionLatestCounts extends PoP_Module_Processor_SectionLatestCountsBase
{
    public final const MODULE_LATESTCOUNT_LOCATIONPOSTS = 'latestcount-locationposts';
    public final const MODULE_LATESTCOUNT_AUTHOR_LOCATIONPOSTS = 'latestcount-author-locationposts';
    public final const MODULE_LATESTCOUNT_TAG_LOCATIONPOSTS = 'latestcount-tag-locationposts';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LATESTCOUNT_LOCATIONPOSTS],
            [self::class, self::MODULE_LATESTCOUNT_AUTHOR_LOCATIONPOSTS],
            [self::class, self::MODULE_LATESTCOUNT_TAG_LOCATIONPOSTS],
        );
    }

    public function getObjectName(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LATESTCOUNT_LOCATIONPOSTS:
            case self::MODULE_LATESTCOUNT_AUTHOR_LOCATIONPOSTS:
            case self::MODULE_LATESTCOUNT_TAG_LOCATIONPOSTS:
                return PoP_LocationPosts_PostNameUtils::getNameLc();
        }
    
        return parent::getObjectNames($componentVariation, $props);
    }

    public function getObjectNames(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LATESTCOUNT_LOCATIONPOSTS:
            case self::MODULE_LATESTCOUNT_AUTHOR_LOCATIONPOSTS:
            case self::MODULE_LATESTCOUNT_TAG_LOCATIONPOSTS:
                return PoP_LocationPosts_PostNameUtils::getNamesLc();
        }
    
        return parent::getObjectNames($componentVariation, $props);
    }

    public function getSectionClasses(array $componentVariation, array &$props)
    {
        $ret = parent::getSectionClasses($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_LATESTCOUNT_LOCATIONPOSTS:
            case self::MODULE_LATESTCOUNT_AUTHOR_LOCATIONPOSTS:
            case self::MODULE_LATESTCOUNT_TAG_LOCATIONPOSTS:
                $ret[] = POP_LOCATIONPOSTS_POSTTYPE_LOCATIONPOST.'-'.POP_LOCATIONPOSTS_CAT_ALL;
                break;
        }

        // Allow to hook in POP_CONTENTPOSTLINKS_CAT_CONTENTPOSTLINKS
        $ret = \PoP\Root\App::applyFilters(
            'latestcounts:locationposts:classes',
            $ret,
            $componentVariation,
            $props
        );
    
        return $ret;
    }

    public function isAuthor(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LATESTCOUNT_AUTHOR_LOCATIONPOSTS:
                return true;
        }
    
        return parent::isAuthor($componentVariation, $props);
    }

    public function isTag(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LATESTCOUNT_TAG_LOCATIONPOSTS:
                return true;
        }
    
        return parent::isTag($componentVariation, $props);
    }
}


