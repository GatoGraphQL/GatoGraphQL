<?php

use PoP\ComponentModel\State\ApplicationState;

abstract class PoP_Module_Processor_SectionLatestCountsBase extends PoP_Module_Processor_LatestCountsBase
{
    public function getClasses(array $component, array &$props)
    {
        $ret = parent::getClasses($component, $props);
        if ($section_classes = $this->getSectionClasses($component, $props)) {
            $pre = '';
            if ($this->isAuthor($component, $props)) {
                $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                $ret[] = 'author'.$author;
                $pre = 'author-';
            } elseif ($this->isSingle($component, $props)) {
                $post_id = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                $ret[] = 'single'.$post_id;
                $pre = 'single-';
            } elseif ($this->isTag($component, $props)) {
                $ret[] = 'tag'.\PoP\Root\App::getState(['routing', 'queried-object-id']);
                $pre = 'tag-';
            }

            foreach ($section_classes as $section_class) {
                $ret[] = $pre.$section_class;
            }

            if ($this->isAuthor($component, $props)) {
                // Allow URE to add the organization members in the organization's feed
                $ret = GD_LatestCounts_Utils::authorFilters($ret, $component, $props);
            }
        }
    
        return $ret;
    }

    public function getSectionClasses(array $component, array &$props)
    {
        return array();
    }

    public function isAuthor(array $component, array &$props)
    {
        return false;
    }

    public function isTag(array $component, array &$props)
    {
        return false;
    }

    public function isSingle(array $component, array &$props)
    {
        return false;
    }
}
