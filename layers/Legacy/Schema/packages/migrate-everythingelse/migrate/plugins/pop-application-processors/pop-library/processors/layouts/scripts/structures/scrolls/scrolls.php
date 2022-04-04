<?php

class PoPApplicationProcessors_Module_Processor_CommentScrolls extends PoP_Module_Processor_ScrollsBase
{
    public final const MODULE_SCROLLLAYOUT_REFERENCEDBY_DETAILS = 'layout-referencedby-scroll-details';
    public final const MODULE_SCROLLLAYOUT_REFERENCEDBY_SIMPLEVIEW = 'layout-referencedby-scroll-simpleview';
    public final const MODULE_SCROLLLAYOUT_REFERENCEDBY_FULLVIEW = 'layout-referencedby-scroll-fullview';
    public final const MODULE_SCROLLLAYOUT_REFERENCEDBY_APPENDABLE = 'layout-referencedby-scroll-appendable';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLLLAYOUT_REFERENCEDBY_DETAILS],
            [self::class, self::MODULE_SCROLLLAYOUT_REFERENCEDBY_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLLLAYOUT_REFERENCEDBY_FULLVIEW],
            [self::class, self::MODULE_SCROLLLAYOUT_REFERENCEDBY_APPENDABLE],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_SCROLLLAYOUT_REFERENCEDBY_DETAILS:
                return [PoPApplicationProcessors_Module_Processor_CommentScrollInners::class, PoPApplicationProcessors_Module_Processor_CommentScrollInners::MODULE_LAYOUTSCROLLINNER_REFERENCEDBY_DETAILS];

            case self::MODULE_SCROLLLAYOUT_REFERENCEDBY_SIMPLEVIEW:
                return [PoPApplicationProcessors_Module_Processor_CommentScrollInners::class, PoPApplicationProcessors_Module_Processor_CommentScrollInners::MODULE_LAYOUTSCROLLINNER_REFERENCEDBY_SIMPLEVIEW];

            case self::MODULE_SCROLLLAYOUT_REFERENCEDBY_FULLVIEW:
                return [PoPApplicationProcessors_Module_Processor_CommentScrollInners::class, PoPApplicationProcessors_Module_Processor_CommentScrollInners::MODULE_LAYOUTSCROLLINNER_REFERENCEDBY_FULLVIEW];

            case self::MODULE_SCROLLLAYOUT_REFERENCEDBY_APPENDABLE:
                return [PoPApplicationProcessors_Module_Processor_CommentScrollInners::class, PoPApplicationProcessors_Module_Processor_CommentScrollInners::MODULE_LAYOUTSCROLLINNER_REFERENCEDBY_APPENDABLE];
        }

        return parent::getInnerSubmodule($module);
    }

    public function addFetchedData(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_SCROLLLAYOUT_REFERENCEDBY_DETAILS:
            case self::MODULE_SCROLLLAYOUT_REFERENCEDBY_SIMPLEVIEW:
            case self::MODULE_SCROLLLAYOUT_REFERENCEDBY_FULLVIEW:
            case self::MODULE_SCROLLLAYOUT_REFERENCEDBY_APPENDABLE:
                return false;
        }

        return parent::addFetchedData($module, $props);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_SCROLLLAYOUT_REFERENCEDBY_APPENDABLE:
                $classes = array(
                    self::MODULE_SCROLLLAYOUT_REFERENCEDBY_APPENDABLE => 'references',
                );

                $this->setProp($module, $props, 'appendable', true);
                $this->setProp($module, $props, 'appendable-class', $classes[$module[1]] ?? null);

                break;
        }

        parent::initModelProps($module, $props);
    }
}


