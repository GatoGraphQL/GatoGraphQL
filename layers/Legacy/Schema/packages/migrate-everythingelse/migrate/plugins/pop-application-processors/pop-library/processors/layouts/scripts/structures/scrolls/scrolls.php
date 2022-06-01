<?php

class PoPApplicationProcessors_Module_Processor_CommentScrolls extends PoP_Module_Processor_ScrollsBase
{
    public final const COMPONENT_SCROLLLAYOUT_REFERENCEDBY_DETAILS = 'layout-referencedby-scroll-details';
    public final const COMPONENT_SCROLLLAYOUT_REFERENCEDBY_SIMPLEVIEW = 'layout-referencedby-scroll-simpleview';
    public final const COMPONENT_SCROLLLAYOUT_REFERENCEDBY_FULLVIEW = 'layout-referencedby-scroll-fullview';
    public final const COMPONENT_SCROLLLAYOUT_REFERENCEDBY_APPENDABLE = 'layout-referencedby-scroll-appendable';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SCROLLLAYOUT_REFERENCEDBY_DETAILS],
            [self::class, self::COMPONENT_SCROLLLAYOUT_REFERENCEDBY_SIMPLEVIEW],
            [self::class, self::COMPONENT_SCROLLLAYOUT_REFERENCEDBY_FULLVIEW],
            [self::class, self::COMPONENT_SCROLLLAYOUT_REFERENCEDBY_APPENDABLE],
        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_SCROLLLAYOUT_REFERENCEDBY_DETAILS:
                return [PoPApplicationProcessors_Module_Processor_CommentScrollInners::class, PoPApplicationProcessors_Module_Processor_CommentScrollInners::COMPONENT_LAYOUTSCROLLINNER_REFERENCEDBY_DETAILS];

            case self::COMPONENT_SCROLLLAYOUT_REFERENCEDBY_SIMPLEVIEW:
                return [PoPApplicationProcessors_Module_Processor_CommentScrollInners::class, PoPApplicationProcessors_Module_Processor_CommentScrollInners::COMPONENT_LAYOUTSCROLLINNER_REFERENCEDBY_SIMPLEVIEW];

            case self::COMPONENT_SCROLLLAYOUT_REFERENCEDBY_FULLVIEW:
                return [PoPApplicationProcessors_Module_Processor_CommentScrollInners::class, PoPApplicationProcessors_Module_Processor_CommentScrollInners::COMPONENT_LAYOUTSCROLLINNER_REFERENCEDBY_FULLVIEW];

            case self::COMPONENT_SCROLLLAYOUT_REFERENCEDBY_APPENDABLE:
                return [PoPApplicationProcessors_Module_Processor_CommentScrollInners::class, PoPApplicationProcessors_Module_Processor_CommentScrollInners::COMPONENT_LAYOUTSCROLLINNER_REFERENCEDBY_APPENDABLE];
        }

        return parent::getInnerSubcomponent($component);
    }

    public function addFetchedData(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_SCROLLLAYOUT_REFERENCEDBY_DETAILS:
            case self::COMPONENT_SCROLLLAYOUT_REFERENCEDBY_SIMPLEVIEW:
            case self::COMPONENT_SCROLLLAYOUT_REFERENCEDBY_FULLVIEW:
            case self::COMPONENT_SCROLLLAYOUT_REFERENCEDBY_APPENDABLE:
                return false;
        }

        return parent::addFetchedData($component, $props);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_SCROLLLAYOUT_REFERENCEDBY_APPENDABLE:
                $classes = array(
                    self::COMPONENT_SCROLLLAYOUT_REFERENCEDBY_APPENDABLE => 'references',
                );

                $this->setProp($component, $props, 'appendable', true);
                $this->setProp($component, $props, 'appendable-class', $classes[$component->name] ?? null);

                break;
        }

        parent::initModelProps($component, $props);
    }
}


