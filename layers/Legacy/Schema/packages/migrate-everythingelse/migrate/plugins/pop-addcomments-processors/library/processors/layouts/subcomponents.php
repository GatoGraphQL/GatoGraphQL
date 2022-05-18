<?php

class PoP_Module_Processor_PostCommentSubcomponentLayouts extends PoP_Module_Processor_SubcomponentLayoutsBase
{
    public final const MODULE_SUBCOMPONENT_POSTCOMMENTS = 'subcomponent-postcomments';
    public final const MODULE_LAZYSUBCOMPONENT_POSTCOMMENTS = 'lazysubcomponent-postcomments';
    public final const MODULE_LAZYSUBCOMPONENT_NOHEADERPOSTCOMMENTS = 'lazysubcomponent-noheaderpostcomments';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SUBCOMPONENT_POSTCOMMENTS],
            [self::class, self::MODULE_LAZYSUBCOMPONENT_POSTCOMMENTS],
            [self::class, self::MODULE_LAZYSUBCOMPONENT_NOHEADERPOSTCOMMENTS],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        switch ($component[1]) {
            case self::MODULE_SUBCOMPONENT_POSTCOMMENTS:
                $ret[] = [PoP_Module_Processor_CommentScrolls::class, PoP_Module_Processor_CommentScrolls::MODULE_SCROLLLAYOUT_POSTCOMMENT];
                break;

            case self::MODULE_LAZYSUBCOMPONENT_POSTCOMMENTS:
            case self::MODULE_LAZYSUBCOMPONENT_NOHEADERPOSTCOMMENTS:
                $ret[] = [PoP_Module_Processor_CommentScrolls::class, PoP_Module_Processor_CommentScrolls::MODULE_SCROLLLAYOUT_POSTCOMMENT_APPENDABLE];
                break;
        }

        return $ret;
    }

    public function getSubcomponentField(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_SUBCOMPONENT_POSTCOMMENTS:
                return 'comments';

            case self::MODULE_LAZYSUBCOMPONENT_POSTCOMMENTS:
                return 'commentsLazy';

            case self::MODULE_LAZYSUBCOMPONENT_NOHEADERPOSTCOMMENTS:
                return 'noheadercommentsLazy';
        }

        return parent::getSubcomponentField($component);
    }

    public function isIndividual(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_SUBCOMPONENT_POSTCOMMENTS:
            case self::MODULE_LAZYSUBCOMPONENT_POSTCOMMENTS:
            case self::MODULE_LAZYSUBCOMPONENT_NOHEADERPOSTCOMMENTS:
                return false;
        }

        return parent::isIndividual($component, $props);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::MODULE_SUBCOMPONENT_POSTCOMMENTS:
            case self::MODULE_LAZYSUBCOMPONENT_POSTCOMMENTS:
            case self::MODULE_LAZYSUBCOMPONENT_NOHEADERPOSTCOMMENTS:
                $this->appendProp($component, $props, 'class', 'postcomments clearfix');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



