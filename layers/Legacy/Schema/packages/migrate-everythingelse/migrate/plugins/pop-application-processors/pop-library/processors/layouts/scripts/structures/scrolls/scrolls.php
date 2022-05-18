<?php

class PoPApplicationProcessors_Module_Processor_CommentScrolls extends PoP_Module_Processor_ScrollsBase
{
    public final const MODULE_SCROLLLAYOUT_REFERENCEDBY_DETAILS = 'layout-referencedby-scroll-details';
    public final const MODULE_SCROLLLAYOUT_REFERENCEDBY_SIMPLEVIEW = 'layout-referencedby-scroll-simpleview';
    public final const MODULE_SCROLLLAYOUT_REFERENCEDBY_FULLVIEW = 'layout-referencedby-scroll-fullview';
    public final const MODULE_SCROLLLAYOUT_REFERENCEDBY_APPENDABLE = 'layout-referencedby-scroll-appendable';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SCROLLLAYOUT_REFERENCEDBY_DETAILS],
            [self::class, self::COMPONENT_SCROLLLAYOUT_REFERENCEDBY_SIMPLEVIEW],
            [self::class, self::COMPONENT_SCROLLLAYOUT_REFERENCEDBY_FULLVIEW],
            [self::class, self::COMPONENT_SCROLLLAYOUT_REFERENCEDBY_APPENDABLE],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_SCROLLLAYOUT_REFERENCEDBY_DETAILS:
                return [PoPApplicationProcessors_Module_Processor_CommentScrollInners::class, PoPApplicationProcessors_Module_Processor_CommentScrollInners::COMPONENT_LAYOUTSCROLLINNER_REFERENCEDBY_DETAILS];

            case self::COMPONENT_SCROLLLAYOUT_REFERENCEDBY_SIMPLEVIEW:
                return [PoPApplicationProcessors_Module_Processor_CommentScrollInners::class, PoPApplicationProcessors_Module_Processor_CommentScrollInners::COMPONENT_LAYOUTSCROLLINNER_REFERENCEDBY_SIMPLEVIEW];

            case self::COMPONENT_SCROLLLAYOUT_REFERENCEDBY_FULLVIEW:
                return [PoPApplicationProcessors_Module_Processor_CommentScrollInners::class, PoPApplicationProcessors_Module_Processor_CommentScrollInners::COMPONENT_LAYOUTSCROLLINNER_REFERENCEDBY_FULLVIEW];

            case self::COMPONENT_SCROLLLAYOUT_REFERENCEDBY_APPENDABLE:
                return [PoPApplicationProcessors_Module_Processor_CommentScrollInners::class, PoPApplicationProcessors_Module_Processor_CommentScrollInners::COMPONENT_LAYOUTSCROLLINNER_REFERENCEDBY_APPENDABLE];
        }

        return parent::getInnerSubmodule($component);
    }

    public function addFetchedData(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_SCROLLLAYOUT_REFERENCEDBY_DETAILS:
            case self::COMPONENT_SCROLLLAYOUT_REFERENCEDBY_SIMPLEVIEW:
            case self::COMPONENT_SCROLLLAYOUT_REFERENCEDBY_FULLVIEW:
            case self::COMPONENT_SCROLLLAYOUT_REFERENCEDBY_APPENDABLE:
                return false;
        }

        return parent::addFetchedData($component, $props);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_SCROLLLAYOUT_REFERENCEDBY_APPENDABLE:
                $classes = array(
                    self::COMPONENT_SCROLLLAYOUT_REFERENCEDBY_APPENDABLE => 'references',
                );

                $this->setProp($component, $props, 'appendable', true);
                $this->setProp($component, $props, 'appendable-class', $classes[$component[1]] ?? null);

                break;
        }

        parent::initModelProps($component, $props);
    }
}


