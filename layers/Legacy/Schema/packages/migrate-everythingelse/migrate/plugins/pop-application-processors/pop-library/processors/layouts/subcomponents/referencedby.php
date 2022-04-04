<?php

class PoP_Module_Processor_ReferencedbyLayouts extends PoP_Module_Processor_SubcomponentLayoutsBase
{
    public final const MODULE_SUBCOMPONENT_REFERENCEDBY_DETAILS = 'subcomponent-referencedby-details';
    public final const MODULE_SUBCOMPONENT_REFERENCEDBY_SIMPLEVIEW = 'subcomponent-referencedby-simpleview';
    public final const MODULE_SUBCOMPONENT_REFERENCEDBY_FULLVIEW = 'subcomponent-referencedby-fullview';
    public final const MODULE_LAZYSUBCOMPONENT_REFERENCEDBY = 'lazysubcomponent-referencedby';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SUBCOMPONENT_REFERENCEDBY_DETAILS],
            [self::class, self::MODULE_SUBCOMPONENT_REFERENCEDBY_SIMPLEVIEW],
            [self::class, self::MODULE_SUBCOMPONENT_REFERENCEDBY_FULLVIEW],
            [self::class, self::MODULE_LAZYSUBCOMPONENT_REFERENCEDBY],
        );
    }

    public function getSubcomponentField(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_SUBCOMPONENT_REFERENCEDBY_DETAILS:
            case self::MODULE_SUBCOMPONENT_REFERENCEDBY_SIMPLEVIEW:
            case self::MODULE_SUBCOMPONENT_REFERENCEDBY_FULLVIEW:
                return 'referencedby';

            case self::MODULE_LAZYSUBCOMPONENT_REFERENCEDBY:
                return 'referencedbyLazy';
        }

        return parent::getSubcomponentField($module);
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_SUBCOMPONENT_REFERENCEDBY_DETAILS:
                $ret[] = [PoPApplicationProcessors_Module_Processor_CommentScrolls::class, PoPApplicationProcessors_Module_Processor_CommentScrolls::MODULE_SCROLLLAYOUT_REFERENCEDBY_DETAILS];
                break;

            case self::MODULE_SUBCOMPONENT_REFERENCEDBY_SIMPLEVIEW:
                $ret[] = [PoPApplicationProcessors_Module_Processor_CommentScrolls::class, PoPApplicationProcessors_Module_Processor_CommentScrolls::MODULE_SCROLLLAYOUT_REFERENCEDBY_SIMPLEVIEW];
                break;

            case self::MODULE_SUBCOMPONENT_REFERENCEDBY_FULLVIEW:
                $ret[] = [PoPApplicationProcessors_Module_Processor_CommentScrolls::class, PoPApplicationProcessors_Module_Processor_CommentScrolls::MODULE_SCROLLLAYOUT_REFERENCEDBY_FULLVIEW];
                break;

            case self::MODULE_LAZYSUBCOMPONENT_REFERENCEDBY:
                $ret[] = [PoPApplicationProcessors_Module_Processor_CommentScrolls::class, PoPApplicationProcessors_Module_Processor_CommentScrolls::MODULE_SCROLLLAYOUT_REFERENCEDBY_APPENDABLE];
                break;
        }

        return $ret;
    }

    public function isIndividual(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_SUBCOMPONENT_REFERENCEDBY_DETAILS:
            case self::MODULE_SUBCOMPONENT_REFERENCEDBY_SIMPLEVIEW:
            case self::MODULE_SUBCOMPONENT_REFERENCEDBY_FULLVIEW:
            case self::MODULE_LAZYSUBCOMPONENT_REFERENCEDBY:
                return false;
        }

        return parent::isIndividual($module, $props);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_SUBCOMPONENT_REFERENCEDBY_DETAILS:
            case self::MODULE_SUBCOMPONENT_REFERENCEDBY_SIMPLEVIEW:
            case self::MODULE_SUBCOMPONENT_REFERENCEDBY_FULLVIEW:
            case self::MODULE_LAZYSUBCOMPONENT_REFERENCEDBY:
                $this->appendProp($module, $props, 'class', 'referencedby clearfix');
                break;
        }

        parent::initModelProps($module, $props);
    }
}



