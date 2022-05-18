<?php

class PoP_EventsCreation_Module_Processor_CustomScrolls extends PoP_Module_Processor_ScrollsBase
{
    public final const MODULE_SCROLL_MYEVENTS_SIMPLEVIEWPREVIEW = 'scroll-myevents-simpleviewpreview';
    public final const MODULE_SCROLL_MYPASTEVENTS_SIMPLEVIEWPREVIEW = 'scroll-mypastevents-simpleviewpreview';
    public final const MODULE_SCROLL_MYEVENTS_FULLVIEWPREVIEW = 'scroll-myevents-fullviewpreview';
    public final const MODULE_SCROLL_MYPASTEVENTS_FULLVIEWPREVIEW = 'scroll-mypastevents-fullviewpreview';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLL_MYEVENTS_SIMPLEVIEWPREVIEW],
            [self::class, self::MODULE_SCROLL_MYPASTEVENTS_SIMPLEVIEWPREVIEW],
            [self::class, self::MODULE_SCROLL_MYEVENTS_FULLVIEWPREVIEW],
            [self::class, self::MODULE_SCROLL_MYPASTEVENTS_FULLVIEWPREVIEW],
        );
    }


    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_SCROLL_MYEVENTS_SIMPLEVIEWPREVIEW => [PoP_EventsCreation_Module_Processor_CustomScrollInners::class, PoP_EventsCreation_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_MYEVENTS_SIMPLEVIEWPREVIEW],
            self::MODULE_SCROLL_MYPASTEVENTS_SIMPLEVIEWPREVIEW => [PoP_EventsCreation_Module_Processor_CustomScrollInners::class, PoP_EventsCreation_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_MYPASTEVENTS_SIMPLEVIEWPREVIEW],
            self::MODULE_SCROLL_MYEVENTS_FULLVIEWPREVIEW => [PoP_EventsCreation_Module_Processor_CustomScrollInners::class, PoP_EventsCreation_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_MYEVENTS_FULLVIEWPREVIEW],
            self::MODULE_SCROLL_MYPASTEVENTS_FULLVIEWPREVIEW => [PoP_EventsCreation_Module_Processor_CustomScrollInners::class, PoP_EventsCreation_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_MYPASTEVENTS_FULLVIEWPREVIEW],
        );
        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }

    public function initModelProps(array $module, array &$props): void
    {

        // Extra classes
        $simpleviews = array(
            [self::class, self::MODULE_SCROLL_MYEVENTS_SIMPLEVIEWPREVIEW],
            [self::class, self::MODULE_SCROLL_MYPASTEVENTS_SIMPLEVIEWPREVIEW],
        );
        $fullviews = array(
            [self::class, self::MODULE_SCROLL_MYEVENTS_FULLVIEWPREVIEW],
            [self::class, self::MODULE_SCROLL_MYPASTEVENTS_FULLVIEWPREVIEW],
        );

        $extra_class = '';
        if (in_array($module, $simpleviews)) {
            $extra_class = 'simpleview';
        } elseif (in_array($module, $fullviews)) {
            $extra_class = 'fullview';
        }
        $this->appendProp($module, $props, 'class', $extra_class);

        parent::initModelProps($module, $props);
    }
}


