<?php

class PoP_ContentPostLinks_Module_Processor_SectionLatestCounts extends PoP_Module_Processor_SectionLatestCountsBase
{
    public final const COMPONENT_LATESTCOUNT_POSTLINKS = 'latestcount-postlinks';
    public final const COMPONENT_LATESTCOUNT_AUTHOR_POSTLINKS = 'latestcount-author-postlinks';
    public final const COMPONENT_LATESTCOUNT_TAG_POSTLINKS = 'latestcount-tag-postlinks';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LATESTCOUNT_POSTLINKS,
            self::COMPONENT_LATESTCOUNT_AUTHOR_POSTLINKS,
            self::COMPONENT_LATESTCOUNT_TAG_POSTLINKS,
        );
    }

    public function getObjectName(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $cats = array(
            self::COMPONENT_LATESTCOUNT_POSTLINKS => POP_CONTENTPOSTLINKS_CAT_CONTENTPOSTLINKS,
            self::COMPONENT_LATESTCOUNT_AUTHOR_POSTLINKS => POP_CONTENTPOSTLINKS_CAT_CONTENTPOSTLINKS,
            self::COMPONENT_LATESTCOUNT_TAG_POSTLINKS => POP_CONTENTPOSTLINKS_CAT_CONTENTPOSTLINKS,
        );
        if ($cat = $cats[$component->name] ?? null) {
            return gdGetCategoryname($cat, 'lc');
        }

        return parent::getObjectNames($component, $props);
    }

    public function getObjectNames(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $cats = array(
            self::COMPONENT_LATESTCOUNT_POSTLINKS => POP_CONTENTPOSTLINKS_CAT_CONTENTPOSTLINKS,
            self::COMPONENT_LATESTCOUNT_AUTHOR_POSTLINKS => POP_CONTENTPOSTLINKS_CAT_CONTENTPOSTLINKS,
            self::COMPONENT_LATESTCOUNT_TAG_POSTLINKS => POP_CONTENTPOSTLINKS_CAT_CONTENTPOSTLINKS,
        );
        if ($cat = $cats[$component->name] ?? null) {
            return gdGetCategoryname($cat, 'plural-lc');
            ;
        }

        return parent::getObjectNames($component, $props);
    }

    public function getSectionClasses(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getSectionClasses($component, $props);

        switch ($component->name) {
            case self::COMPONENT_LATESTCOUNT_POSTLINKS:
            case self::COMPONENT_LATESTCOUNT_AUTHOR_POSTLINKS:
            case self::COMPONENT_LATESTCOUNT_TAG_POSTLINKS:
                $ret[] = 'post-'.POP_CONTENTPOSTLINKS_CAT_CONTENTPOSTLINKS;
                break;
        }

        return $ret;
    }

    public function isAuthor(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_LATESTCOUNT_AUTHOR_POSTLINKS:
                return true;
        }

        return parent::isAuthor($component, $props);
    }

    public function isTag(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_LATESTCOUNT_TAG_POSTLINKS:
                return true;
        }

        return parent::isTag($component, $props);
    }
}


