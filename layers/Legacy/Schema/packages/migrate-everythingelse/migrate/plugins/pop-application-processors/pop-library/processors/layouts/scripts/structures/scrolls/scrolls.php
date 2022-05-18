<?php

class PoPApplicationProcessors_Module_Processor_CommentScrolls extends PoP_Module_Processor_ScrollsBase
{
    public final const MODULE_SCROLLLAYOUT_REFERENCEDBY_DETAILS = 'layout-referencedby-scroll-details';
    public final const MODULE_SCROLLLAYOUT_REFERENCEDBY_SIMPLEVIEW = 'layout-referencedby-scroll-simpleview';
    public final const MODULE_SCROLLLAYOUT_REFERENCEDBY_FULLVIEW = 'layout-referencedby-scroll-fullview';
    public final const MODULE_SCROLLLAYOUT_REFERENCEDBY_APPENDABLE = 'layout-referencedby-scroll-appendable';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLLLAYOUT_REFERENCEDBY_DETAILS],
            [self::class, self::MODULE_SCROLLLAYOUT_REFERENCEDBY_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLLLAYOUT_REFERENCEDBY_FULLVIEW],
            [self::class, self::MODULE_SCROLLLAYOUT_REFERENCEDBY_APPENDABLE],
        );
    }

    public function getInnerSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_SCROLLLAYOUT_REFERENCEDBY_DETAILS:
                return [PoPApplicationProcessors_Module_Processor_CommentScrollInners::class, PoPApplicationProcessors_Module_Processor_CommentScrollInners::MODULE_LAYOUTSCROLLINNER_REFERENCEDBY_DETAILS];

            case self::MODULE_SCROLLLAYOUT_REFERENCEDBY_SIMPLEVIEW:
                return [PoPApplicationProcessors_Module_Processor_CommentScrollInners::class, PoPApplicationProcessors_Module_Processor_CommentScrollInners::MODULE_LAYOUTSCROLLINNER_REFERENCEDBY_SIMPLEVIEW];

            case self::MODULE_SCROLLLAYOUT_REFERENCEDBY_FULLVIEW:
                return [PoPApplicationProcessors_Module_Processor_CommentScrollInners::class, PoPApplicationProcessors_Module_Processor_CommentScrollInners::MODULE_LAYOUTSCROLLINNER_REFERENCEDBY_FULLVIEW];

            case self::MODULE_SCROLLLAYOUT_REFERENCEDBY_APPENDABLE:
                return [PoPApplicationProcessors_Module_Processor_CommentScrollInners::class, PoPApplicationProcessors_Module_Processor_CommentScrollInners::MODULE_LAYOUTSCROLLINNER_REFERENCEDBY_APPENDABLE];
        }

        return parent::getInnerSubmodule($componentVariation);
    }

    public function addFetchedData(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_SCROLLLAYOUT_REFERENCEDBY_DETAILS:
            case self::MODULE_SCROLLLAYOUT_REFERENCEDBY_SIMPLEVIEW:
            case self::MODULE_SCROLLLAYOUT_REFERENCEDBY_FULLVIEW:
            case self::MODULE_SCROLLLAYOUT_REFERENCEDBY_APPENDABLE:
                return false;
        }

        return parent::addFetchedData($componentVariation, $props);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_SCROLLLAYOUT_REFERENCEDBY_APPENDABLE:
                $classes = array(
                    self::MODULE_SCROLLLAYOUT_REFERENCEDBY_APPENDABLE => 'references',
                );

                $this->setProp($componentVariation, $props, 'appendable', true);
                $this->setProp($componentVariation, $props, 'appendable-class', $classes[$componentVariation[1]] ?? null);

                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}


