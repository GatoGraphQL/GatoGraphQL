<?php

use PoP\ComponentModel\State\ApplicationState;

class PoP_Module_Processor_LatestCounts extends PoP_Module_Processor_LatestCountsBase
{
    public final const COMPONENT_LATESTCOUNT_TAG_CONTENT = 'latestcount-tag-content';
    public final const COMPONENT_LATESTCOUNT_CONTENT = 'latestcount-content';
    public final const COMPONENT_LATESTCOUNT_AUTHOR_CONTENT = 'latestcount-author-content';
    public final const COMPONENT_LATESTCOUNT_SINGLE_CONTENT = 'latestcount-single-content';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LATESTCOUNT_TAG_CONTENT,
            self::COMPONENT_LATESTCOUNT_CONTENT,
            self::COMPONENT_LATESTCOUNT_AUTHOR_CONTENT,
            self::COMPONENT_LATESTCOUNT_SINGLE_CONTENT,
        );
    }

    public function getClasses(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_LATESTCOUNT_TAG_CONTENT:
                return array(
                    'tag'.\PoP\Root\App::getState(['routing', 'queried-object-id'])
                );
            
            case self::COMPONENT_LATESTCOUNT_CONTENT:
                return GD_LatestCounts_Utils::getAllcontentClasses($component, $props);
            
            case self::COMPONENT_LATESTCOUNT_AUTHOR_CONTENT:
                $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                $ret = array(
                    'author'.$author
                );

                // Add prefix "author" before each class
                $classes = GD_LatestCounts_Utils::getAllcontentClasses($component, $props);
                foreach ($classes as $class) {
                    $ret[] = 'author-'.$class;
                }

                return GD_LatestCounts_Utils::authorFilters($ret, $component, $props);
            
            case self::COMPONENT_LATESTCOUNT_SINGLE_CONTENT:
                $post_id = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                $ret = array(
                    'single'.$post_id
                );

                // Add prefix "single" before each class
                $classes = GD_LatestCounts_Utils::getAllcontentClasses($component, $props);
                foreach ($classes as $class) {
                    $ret[] = 'single-'.$class;
                }

                return $ret;
        }
    
        return parent::getClasses($component, $props);
    }
}


