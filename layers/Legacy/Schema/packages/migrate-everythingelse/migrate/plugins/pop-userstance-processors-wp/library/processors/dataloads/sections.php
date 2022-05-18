<?php

class UserStance_WP_Module_Processor_CustomSectionDataloads extends UserStance_Module_Processor_CustomSectionDataloads
{
    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_AUTHORSTANCES_CAROUSEL],
        );
    }

    protected function getImmutableDataloadQueryArgs(array $module, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($module, $props);
        
        switch ($module[1]) {
            // Order the Author Thoughts Carousel, so that it always shows the General thought first, and the then article-related ones
            case self::MODULE_DATALOAD_AUTHORSTANCES_CAROUSEL:
                // General thought: menu_order = 0. Article-related thought: menu_order = 1. So order ASC.
                $ret['orderby'] = [
                  'menu_order' => 'ASC', 
                  'date' => 'DESC',
                ];
                break;
        }

        return $ret;
    }
}

