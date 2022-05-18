<?php

class GD_URE_Module_Processor_CustomScrolls extends PoP_Module_Processor_ScrollsBase
{
    public final const MODULE_SCROLL_ORGANIZATIONS_DETAILS = 'scroll-organizations-details';
    public final const MODULE_SCROLL_INDIVIDUALS_DETAILS = 'scroll-individuals-details';
    public final const MODULE_SCROLL_ORGANIZATIONS_FULLVIEW = 'scroll-organizations-fullview';
    public final const MODULE_SCROLL_INDIVIDUALS_FULLVIEW = 'scroll-individuals-fullview';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLL_ORGANIZATIONS_DETAILS],
            [self::class, self::MODULE_SCROLL_INDIVIDUALS_DETAILS],
            [self::class, self::MODULE_SCROLL_ORGANIZATIONS_FULLVIEW],
            [self::class, self::MODULE_SCROLL_INDIVIDUALS_FULLVIEW],
        );
    }


    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_SCROLL_ORGANIZATIONS_DETAILS => [GD_URE_Module_Processor_CustomScrollInners::class, GD_URE_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_ORGANIZATIONS_DETAILS],
            self::MODULE_SCROLL_INDIVIDUALS_DETAILS => [GD_URE_Module_Processor_CustomScrollInners::class, GD_URE_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_INDIVIDUALS_DETAILS],
            self::MODULE_SCROLL_ORGANIZATIONS_FULLVIEW => [GD_URE_Module_Processor_CustomScrollInners::class, GD_URE_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_ORGANIZATIONS_FULLVIEW],
            self::MODULE_SCROLL_INDIVIDUALS_FULLVIEW => [GD_URE_Module_Processor_CustomScrollInners::class, GD_URE_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_INDIVIDUALS_FULLVIEW],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }

    public function initModelProps(array $module, array &$props): void
    {

        // Extra classes
        $details = array(
            [self::class, self::MODULE_SCROLL_ORGANIZATIONS_DETAILS],
            [self::class, self::MODULE_SCROLL_INDIVIDUALS_DETAILS],
        );
        $fullviews = array(
            [self::class, self::MODULE_SCROLL_ORGANIZATIONS_FULLVIEW],
            [self::class, self::MODULE_SCROLL_INDIVIDUALS_FULLVIEW],
        );

        $extra_class = '';
        if (in_array($module, $fullviews)) {
            $extra_class = 'fullview';
        } elseif (in_array($module, $details)) {
            $extra_class = 'details';
        }
        $this->appendProp($module, $props, 'class', $extra_class);

        parent::initModelProps($module, $props);
    }
}


