<?php

class PoP_ContentPostLinks_Module_Processor_SectionLatestCounts extends PoP_Module_Processor_SectionLatestCountsBase
{
    public final const MODULE_LATESTCOUNT_POSTLINKS = 'latestcount-postlinks';
    public final const MODULE_LATESTCOUNT_AUTHOR_POSTLINKS = 'latestcount-author-postlinks';
    public final const MODULE_LATESTCOUNT_TAG_POSTLINKS = 'latestcount-tag-postlinks';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LATESTCOUNT_POSTLINKS],
            [self::class, self::MODULE_LATESTCOUNT_AUTHOR_POSTLINKS],
            [self::class, self::MODULE_LATESTCOUNT_TAG_POSTLINKS],
        );
    }

    public function getObjectName(array $componentVariation, array &$props)
    {
        $cats = array(
            self::MODULE_LATESTCOUNT_POSTLINKS => POP_CONTENTPOSTLINKS_CAT_CONTENTPOSTLINKS,
            self::MODULE_LATESTCOUNT_AUTHOR_POSTLINKS => POP_CONTENTPOSTLINKS_CAT_CONTENTPOSTLINKS,
            self::MODULE_LATESTCOUNT_TAG_POSTLINKS => POP_CONTENTPOSTLINKS_CAT_CONTENTPOSTLINKS,
        );
        if ($cat = $cats[$componentVariation[1]] ?? null) {
            return gdGetCategoryname($cat, 'lc');
        }

        return parent::getObjectNames($componentVariation, $props);
    }

    public function getObjectNames(array $componentVariation, array &$props)
    {
        $cats = array(
            self::MODULE_LATESTCOUNT_POSTLINKS => POP_CONTENTPOSTLINKS_CAT_CONTENTPOSTLINKS,
            self::MODULE_LATESTCOUNT_AUTHOR_POSTLINKS => POP_CONTENTPOSTLINKS_CAT_CONTENTPOSTLINKS,
            self::MODULE_LATESTCOUNT_TAG_POSTLINKS => POP_CONTENTPOSTLINKS_CAT_CONTENTPOSTLINKS,
        );
        if ($cat = $cats[$componentVariation[1]] ?? null) {
            return gdGetCategoryname($cat, 'plural-lc');
            ;
        }

        return parent::getObjectNames($componentVariation, $props);
    }

    public function getSectionClasses(array $componentVariation, array &$props)
    {
        $ret = parent::getSectionClasses($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_LATESTCOUNT_POSTLINKS:
            case self::MODULE_LATESTCOUNT_AUTHOR_POSTLINKS:
            case self::MODULE_LATESTCOUNT_TAG_POSTLINKS:
                $ret[] = 'post-'.POP_CONTENTPOSTLINKS_CAT_CONTENTPOSTLINKS;
                break;
        }

        return $ret;
    }

    public function isAuthor(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LATESTCOUNT_AUTHOR_POSTLINKS:
                return true;
        }

        return parent::isAuthor($componentVariation, $props);
    }

    public function isTag(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LATESTCOUNT_TAG_POSTLINKS:
                return true;
        }

        return parent::isTag($componentVariation, $props);
    }
}


