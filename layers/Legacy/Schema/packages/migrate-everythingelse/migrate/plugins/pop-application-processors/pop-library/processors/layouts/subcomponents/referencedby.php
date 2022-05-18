<?php

class PoP_Module_Processor_ReferencedbyLayouts extends PoP_Module_Processor_SubcomponentLayoutsBase
{
    public final const COMPONENT_SUBCOMPONENT_REFERENCEDBY_DETAILS = 'subcomponent-referencedby-details';
    public final const COMPONENT_SUBCOMPONENT_REFERENCEDBY_SIMPLEVIEW = 'subcomponent-referencedby-simpleview';
    public final const COMPONENT_SUBCOMPONENT_REFERENCEDBY_FULLVIEW = 'subcomponent-referencedby-fullview';
    public final const COMPONENT_LAZYSUBCOMPONENT_REFERENCEDBY = 'lazysubcomponent-referencedby';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SUBCOMPONENT_REFERENCEDBY_DETAILS],
            [self::class, self::COMPONENT_SUBCOMPONENT_REFERENCEDBY_SIMPLEVIEW],
            [self::class, self::COMPONENT_SUBCOMPONENT_REFERENCEDBY_FULLVIEW],
            [self::class, self::COMPONENT_LAZYSUBCOMPONENT_REFERENCEDBY],
        );
    }

    public function getSubcomponentField(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_SUBCOMPONENT_REFERENCEDBY_DETAILS:
            case self::COMPONENT_SUBCOMPONENT_REFERENCEDBY_SIMPLEVIEW:
            case self::COMPONENT_SUBCOMPONENT_REFERENCEDBY_FULLVIEW:
                return 'referencedby';

            case self::COMPONENT_LAZYSUBCOMPONENT_REFERENCEDBY:
                return 'referencedbyLazy';
        }

        return parent::getSubcomponentField($component);
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_SUBCOMPONENT_REFERENCEDBY_DETAILS:
                $ret[] = [PoPApplicationProcessors_Module_Processor_CommentScrolls::class, PoPApplicationProcessors_Module_Processor_CommentScrolls::COMPONENT_SCROLLLAYOUT_REFERENCEDBY_DETAILS];
                break;

            case self::COMPONENT_SUBCOMPONENT_REFERENCEDBY_SIMPLEVIEW:
                $ret[] = [PoPApplicationProcessors_Module_Processor_CommentScrolls::class, PoPApplicationProcessors_Module_Processor_CommentScrolls::COMPONENT_SCROLLLAYOUT_REFERENCEDBY_SIMPLEVIEW];
                break;

            case self::COMPONENT_SUBCOMPONENT_REFERENCEDBY_FULLVIEW:
                $ret[] = [PoPApplicationProcessors_Module_Processor_CommentScrolls::class, PoPApplicationProcessors_Module_Processor_CommentScrolls::COMPONENT_SCROLLLAYOUT_REFERENCEDBY_FULLVIEW];
                break;

            case self::COMPONENT_LAZYSUBCOMPONENT_REFERENCEDBY:
                $ret[] = [PoPApplicationProcessors_Module_Processor_CommentScrolls::class, PoPApplicationProcessors_Module_Processor_CommentScrolls::COMPONENT_SCROLLLAYOUT_REFERENCEDBY_APPENDABLE];
                break;
        }

        return $ret;
    }

    public function isIndividual(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_SUBCOMPONENT_REFERENCEDBY_DETAILS:
            case self::COMPONENT_SUBCOMPONENT_REFERENCEDBY_SIMPLEVIEW:
            case self::COMPONENT_SUBCOMPONENT_REFERENCEDBY_FULLVIEW:
            case self::COMPONENT_LAZYSUBCOMPONENT_REFERENCEDBY:
                return false;
        }

        return parent::isIndividual($component, $props);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_SUBCOMPONENT_REFERENCEDBY_DETAILS:
            case self::COMPONENT_SUBCOMPONENT_REFERENCEDBY_SIMPLEVIEW:
            case self::COMPONENT_SUBCOMPONENT_REFERENCEDBY_FULLVIEW:
            case self::COMPONENT_LAZYSUBCOMPONENT_REFERENCEDBY:
                $this->appendProp($component, $props, 'class', 'referencedby clearfix');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



