<?php

class PoP_Module_Processor_PostCommentSubcomponentLayouts extends PoP_Module_Processor_SubcomponentLayoutsBase
{
    public final const COMPONENT_SUBCOMPONENT_POSTCOMMENTS = 'subcomponent-postcomments';
    public final const COMPONENT_LAZYSUBCOMPONENT_POSTCOMMENTS = 'lazysubcomponent-postcomments';
    public final const COMPONENT_LAZYSUBCOMPONENT_NOHEADERPOSTCOMMENTS = 'lazysubcomponent-noheaderpostcomments';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_SUBCOMPONENT_POSTCOMMENTS,
            self::COMPONENT_LAZYSUBCOMPONENT_POSTCOMMENTS,
            self::COMPONENT_LAZYSUBCOMPONENT_NOHEADERPOSTCOMMENTS,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_SUBCOMPONENT_POSTCOMMENTS:
                $ret[] = [PoP_Module_Processor_CommentScrolls::class, PoP_Module_Processor_CommentScrolls::COMPONENT_SCROLLLAYOUT_POSTCOMMENT];
                break;

            case self::COMPONENT_LAZYSUBCOMPONENT_POSTCOMMENTS:
            case self::COMPONENT_LAZYSUBCOMPONENT_NOHEADERPOSTCOMMENTS:
                $ret[] = [PoP_Module_Processor_CommentScrolls::class, PoP_Module_Processor_CommentScrolls::COMPONENT_SCROLLLAYOUT_POSTCOMMENT_APPENDABLE];
                break;
        }

        return $ret;
    }

    public function getSubcomponentField(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_SUBCOMPONENT_POSTCOMMENTS:
                return 'comments';

            case self::COMPONENT_LAZYSUBCOMPONENT_POSTCOMMENTS:
                return 'commentsLazy';

            case self::COMPONENT_LAZYSUBCOMPONENT_NOHEADERPOSTCOMMENTS:
                return 'noheadercommentsLazy';
        }

        return parent::getSubcomponentField($component);
    }

    public function isIndividual(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_SUBCOMPONENT_POSTCOMMENTS:
            case self::COMPONENT_LAZYSUBCOMPONENT_POSTCOMMENTS:
            case self::COMPONENT_LAZYSUBCOMPONENT_NOHEADERPOSTCOMMENTS:
                return false;
        }

        return parent::isIndividual($component, $props);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_SUBCOMPONENT_POSTCOMMENTS:
            case self::COMPONENT_LAZYSUBCOMPONENT_POSTCOMMENTS:
            case self::COMPONENT_LAZYSUBCOMPONENT_NOHEADERPOSTCOMMENTS:
                $this->appendProp($component, $props, 'class', 'postcomments clearfix');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



