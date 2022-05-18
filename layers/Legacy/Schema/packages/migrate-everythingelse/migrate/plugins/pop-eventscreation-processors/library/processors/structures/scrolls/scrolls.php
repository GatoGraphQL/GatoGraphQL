<?php

class PoP_EventsCreation_Module_Processor_CustomScrolls extends PoP_Module_Processor_ScrollsBase
{
    public final const COMPONENT_SCROLL_MYEVENTS_SIMPLEVIEWPREVIEW = 'scroll-myevents-simpleviewpreview';
    public final const COMPONENT_SCROLL_MYPASTEVENTS_SIMPLEVIEWPREVIEW = 'scroll-mypastevents-simpleviewpreview';
    public final const COMPONENT_SCROLL_MYEVENTS_FULLVIEWPREVIEW = 'scroll-myevents-fullviewpreview';
    public final const COMPONENT_SCROLL_MYPASTEVENTS_FULLVIEWPREVIEW = 'scroll-mypastevents-fullviewpreview';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SCROLL_MYEVENTS_SIMPLEVIEWPREVIEW],
            [self::class, self::COMPONENT_SCROLL_MYPASTEVENTS_SIMPLEVIEWPREVIEW],
            [self::class, self::COMPONENT_SCROLL_MYEVENTS_FULLVIEWPREVIEW],
            [self::class, self::COMPONENT_SCROLL_MYPASTEVENTS_FULLVIEWPREVIEW],
        );
    }


    public function getInnerSubmodule(array $component)
    {
        $inners = array(
            self::COMPONENT_SCROLL_MYEVENTS_SIMPLEVIEWPREVIEW => [PoP_EventsCreation_Module_Processor_CustomScrollInners::class, PoP_EventsCreation_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_MYEVENTS_SIMPLEVIEWPREVIEW],
            self::COMPONENT_SCROLL_MYPASTEVENTS_SIMPLEVIEWPREVIEW => [PoP_EventsCreation_Module_Processor_CustomScrollInners::class, PoP_EventsCreation_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_MYPASTEVENTS_SIMPLEVIEWPREVIEW],
            self::COMPONENT_SCROLL_MYEVENTS_FULLVIEWPREVIEW => [PoP_EventsCreation_Module_Processor_CustomScrollInners::class, PoP_EventsCreation_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_MYEVENTS_FULLVIEWPREVIEW],
            self::COMPONENT_SCROLL_MYPASTEVENTS_FULLVIEWPREVIEW => [PoP_EventsCreation_Module_Processor_CustomScrollInners::class, PoP_EventsCreation_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_MYPASTEVENTS_FULLVIEWPREVIEW],
        );
        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }

    public function initModelProps(array $component, array &$props): void
    {

        // Extra classes
        $simpleviews = array(
            [self::class, self::COMPONENT_SCROLL_MYEVENTS_SIMPLEVIEWPREVIEW],
            [self::class, self::COMPONENT_SCROLL_MYPASTEVENTS_SIMPLEVIEWPREVIEW],
        );
        $fullviews = array(
            [self::class, self::COMPONENT_SCROLL_MYEVENTS_FULLVIEWPREVIEW],
            [self::class, self::COMPONENT_SCROLL_MYPASTEVENTS_FULLVIEWPREVIEW],
        );

        $extra_class = '';
        if (in_array($component, $simpleviews)) {
            $extra_class = 'simpleview';
        } elseif (in_array($component, $fullviews)) {
            $extra_class = 'fullview';
        }
        $this->appendProp($component, $props, 'class', $extra_class);

        parent::initModelProps($component, $props);
    }
}


