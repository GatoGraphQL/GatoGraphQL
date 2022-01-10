<?php

use PoP\ComponentModel\State\ApplicationState;

abstract class PoP_Module_Processor_SectionLatestCountsBase extends PoP_Module_Processor_LatestCountsBase
{
    public function getClasses(array $module, array &$props)
    {
        $vars = ApplicationState::getVars();
        $ret = parent::getClasses($module, $props);
        if ($section_classes = $this->getSectionClasses($module, $props)) {
            $pre = '';
            if ($this->isAuthor($module, $props)) {
                $author = $vars['routing']['queried-object-id'];
                $ret[] = 'author'.$author;
                $pre = 'author-';
            } elseif ($this->isSingle($module, $props)) {
                $post_id = $vars['routing']['queried-object-id'];
                $ret[] = 'single'.$post_id;
                $pre = 'single-';
            } elseif ($this->isTag($module, $props)) {
                $ret[] = 'tag'.$vars['routing']['queried-object-id'];
                $pre = 'tag-';
            }

            foreach ($section_classes as $section_class) {
                $ret[] = $pre.$section_class;
            }

            if ($this->isAuthor($module, $props)) {
                // Allow URE to add the organization members in the organization's feed
                $ret = GD_LatestCounts_Utils::authorFilters($ret, $module, $props);
            }
        }
    
        return $ret;
    }

    public function getSectionClasses(array $module, array &$props)
    {
        return array();
    }

    public function isAuthor(array $module, array &$props)
    {
        return false;
    }

    public function isTag(array $module, array &$props)
    {
        return false;
    }

    public function isSingle(array $module, array &$props)
    {
        return false;
    }
}
