<?php

use PoP\ComponentModel\State\ApplicationState;

abstract class PoP_Module_Processor_SectionLatestCountsBase extends PoP_Module_Processor_LatestCountsBase
{
    public function getClasses(array $componentVariation, array &$props)
    {
        $ret = parent::getClasses($componentVariation, $props);
        if ($section_classes = $this->getSectionClasses($componentVariation, $props)) {
            $pre = '';
            if ($this->isAuthor($componentVariation, $props)) {
                $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                $ret[] = 'author'.$author;
                $pre = 'author-';
            } elseif ($this->isSingle($componentVariation, $props)) {
                $post_id = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                $ret[] = 'single'.$post_id;
                $pre = 'single-';
            } elseif ($this->isTag($componentVariation, $props)) {
                $ret[] = 'tag'.\PoP\Root\App::getState(['routing', 'queried-object-id']);
                $pre = 'tag-';
            }

            foreach ($section_classes as $section_class) {
                $ret[] = $pre.$section_class;
            }

            if ($this->isAuthor($componentVariation, $props)) {
                // Allow URE to add the organization members in the organization's feed
                $ret = GD_LatestCounts_Utils::authorFilters($ret, $componentVariation, $props);
            }
        }
    
        return $ret;
    }

    public function getSectionClasses(array $componentVariation, array &$props)
    {
        return array();
    }

    public function isAuthor(array $componentVariation, array &$props)
    {
        return false;
    }

    public function isTag(array $componentVariation, array &$props)
    {
        return false;
    }

    public function isSingle(array $componentVariation, array &$props)
    {
        return false;
    }
}
