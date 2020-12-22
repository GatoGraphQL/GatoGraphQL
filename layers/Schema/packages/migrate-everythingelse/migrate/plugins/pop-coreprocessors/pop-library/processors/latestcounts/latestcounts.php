<?php

use PoP\ComponentModel\State\ApplicationState;

class PoP_Module_Processor_LatestCounts extends PoP_Module_Processor_LatestCountsBase
{
    public const MODULE_LATESTCOUNT_TAG_CONTENT = 'latestcount-tag-content';
    public const MODULE_LATESTCOUNT_CONTENT = 'latestcount-content';
    public const MODULE_LATESTCOUNT_AUTHOR_CONTENT = 'latestcount-author-content';
    public const MODULE_LATESTCOUNT_SINGLE_CONTENT = 'latestcount-single-content';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LATESTCOUNT_TAG_CONTENT],
            [self::class, self::MODULE_LATESTCOUNT_CONTENT],
            [self::class, self::MODULE_LATESTCOUNT_AUTHOR_CONTENT],
            [self::class, self::MODULE_LATESTCOUNT_SINGLE_CONTENT],
        );
    }

    public function getClasses(array $module, array &$props)
    {
        $vars = ApplicationState::getVars();
        switch ($module[1]) {
            case self::MODULE_LATESTCOUNT_TAG_CONTENT:
                return array(
                    'tag'.$vars['routing-state']['queried-object-id']
                );
            
            case self::MODULE_LATESTCOUNT_CONTENT:
                return GD_LatestCounts_Utils::getAllcontentClasses($module, $props);
            
            case self::MODULE_LATESTCOUNT_AUTHOR_CONTENT:
                $author = $vars['routing-state']['queried-object-id'];
                $ret = array(
                    'author'.$author
                );

                // Add prefix "author" before each class
                $classes = GD_LatestCounts_Utils::getAllcontentClasses($module, $props);
                foreach ($classes as $class) {
                    $ret[] = 'author-'.$class;
                }

                return GD_LatestCounts_Utils::authorFilters($ret, $module, $props);
            
            case self::MODULE_LATESTCOUNT_SINGLE_CONTENT:
                $post_id = $vars['routing-state']['queried-object-id'];
                $ret = array(
                    'single'.$post_id
                );

                // Add prefix "single" before each class
                $classes = GD_LatestCounts_Utils::getAllcontentClasses($module, $props);
                foreach ($classes as $class) {
                    $ret[] = 'single-'.$class;
                }

                return $ret;
        }
    
        return parent::getClasses($module, $props);
    }
}


