<?php

class PoP_ContentPostLinks_Module_Processor_SectionLatestCounts extends PoP_Module_Processor_SectionLatestCountsBase
{
    public final const COMPONENT_LATESTCOUNT_POSTLINKS = 'latestcount-postlinks';
    public final const COMPONENT_LATESTCOUNT_AUTHOR_POSTLINKS = 'latestcount-author-postlinks';
    public final const COMPONENT_LATESTCOUNT_TAG_POSTLINKS = 'latestcount-tag-postlinks';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LATESTCOUNT_POSTLINKS],
            [self::class, self::COMPONENT_LATESTCOUNT_AUTHOR_POSTLINKS],
            [self::class, self::COMPONENT_LATESTCOUNT_TAG_POSTLINKS],
        );
    }

    public function getObjectName(array $component, array &$props)
    {
        $cats = array(
            self::COMPONENT_LATESTCOUNT_POSTLINKS => POP_CONTENTPOSTLINKS_CAT_CONTENTPOSTLINKS,
            self::COMPONENT_LATESTCOUNT_AUTHOR_POSTLINKS => POP_CONTENTPOSTLINKS_CAT_CONTENTPOSTLINKS,
            self::COMPONENT_LATESTCOUNT_TAG_POSTLINKS => POP_CONTENTPOSTLINKS_CAT_CONTENTPOSTLINKS,
        );
        if ($cat = $cats[$component[1]] ?? null) {
            return gdGetCategoryname($cat, 'lc');
        }

        return parent::getObjectNames($component, $props);
    }

    public function getObjectNames(array $component, array &$props)
    {
        $cats = array(
            self::COMPONENT_LATESTCOUNT_POSTLINKS => POP_CONTENTPOSTLINKS_CAT_CONTENTPOSTLINKS,
            self::COMPONENT_LATESTCOUNT_AUTHOR_POSTLINKS => POP_CONTENTPOSTLINKS_CAT_CONTENTPOSTLINKS,
            self::COMPONENT_LATESTCOUNT_TAG_POSTLINKS => POP_CONTENTPOSTLINKS_CAT_CONTENTPOSTLINKS,
        );
        if ($cat = $cats[$component[1]] ?? null) {
            return gdGetCategoryname($cat, 'plural-lc');
            ;
        }

        return parent::getObjectNames($component, $props);
    }

    public function getSectionClasses(array $component, array &$props)
    {
        $ret = parent::getSectionClasses($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_LATESTCOUNT_POSTLINKS:
            case self::COMPONENT_LATESTCOUNT_AUTHOR_POSTLINKS:
            case self::COMPONENT_LATESTCOUNT_TAG_POSTLINKS:
                $ret[] = 'post-'.POP_CONTENTPOSTLINKS_CAT_CONTENTPOSTLINKS;
                break;
        }

        return $ret;
    }

    public function isAuthor(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_LATESTCOUNT_AUTHOR_POSTLINKS:
                return true;
        }

        return parent::isAuthor($component, $props);
    }

    public function isTag(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_LATESTCOUNT_TAG_POSTLINKS:
                return true;
        }

        return parent::isTag($component, $props);
    }
}


