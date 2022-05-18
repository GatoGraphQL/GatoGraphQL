<?php

class PoP_Module_Processor_PostCommentSubcomponentLayouts extends PoP_Module_Processor_SubcomponentLayoutsBase
{
    public final const MODULE_SUBCOMPONENT_POSTCOMMENTS = 'subcomponent-postcomments';
    public final const MODULE_LAZYSUBCOMPONENT_POSTCOMMENTS = 'lazysubcomponent-postcomments';
    public final const MODULE_LAZYSUBCOMPONENT_NOHEADERPOSTCOMMENTS = 'lazysubcomponent-noheaderpostcomments';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SUBCOMPONENT_POSTCOMMENTS],
            [self::class, self::MODULE_LAZYSUBCOMPONENT_POSTCOMMENTS],
            [self::class, self::MODULE_LAZYSUBCOMPONENT_NOHEADERPOSTCOMMENTS],
        );
    }

    public function getLayoutSubmodules(array $componentVariation)
    {
        $ret = parent::getLayoutSubmodules($componentVariation);

        switch ($componentVariation[1]) {
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

    public function getSubcomponentField(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_SUBCOMPONENT_POSTCOMMENTS:
                return 'comments';

            case self::MODULE_LAZYSUBCOMPONENT_POSTCOMMENTS:
                return 'commentsLazy';

            case self::MODULE_LAZYSUBCOMPONENT_NOHEADERPOSTCOMMENTS:
                return 'noheadercommentsLazy';
        }

        return parent::getSubcomponentField($componentVariation);
    }

    public function isIndividual(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_SUBCOMPONENT_POSTCOMMENTS:
            case self::MODULE_LAZYSUBCOMPONENT_POSTCOMMENTS:
            case self::MODULE_LAZYSUBCOMPONENT_NOHEADERPOSTCOMMENTS:
                return false;
        }

        return parent::isIndividual($componentVariation, $props);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_SUBCOMPONENT_POSTCOMMENTS:
            case self::MODULE_LAZYSUBCOMPONENT_POSTCOMMENTS:
            case self::MODULE_LAZYSUBCOMPONENT_NOHEADERPOSTCOMMENTS:
                $this->appendProp($componentVariation, $props, 'class', 'postcomments clearfix');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



