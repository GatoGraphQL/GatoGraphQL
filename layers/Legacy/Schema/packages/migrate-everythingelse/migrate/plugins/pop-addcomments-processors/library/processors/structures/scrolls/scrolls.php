<?php

use PoP\ComponentModel\State\ApplicationState;

class PoP_Module_Processor_CommentScrolls extends PoP_Module_Processor_ScrollsBase
{
    public final const MODULE_SCROLL_COMMENTS_LIST = 'scroll-comments-list';
    public final const MODULE_SCROLL_COMMENTS_ADD = 'scroll-comments-add';
    public final const MODULE_SCROLLLAYOUT_POSTCOMMENT = 'layout-postcomment-scroll';
    public final const MODULE_SCROLLLAYOUT_POSTCOMMENT_APPENDABLE = 'layout-postcomment-scroll-appendable';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLL_COMMENTS_LIST],
            [self::class, self::MODULE_SCROLL_COMMENTS_ADD],
            [self::class, self::MODULE_SCROLLLAYOUT_POSTCOMMENT],
            [self::class, self::MODULE_SCROLLLAYOUT_POSTCOMMENT_APPENDABLE],
        );
    }

    public function getInnerSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_SCROLL_COMMENTS_LIST:
                return [PoP_Module_Processor_CommentScrollInners::class, PoP_Module_Processor_CommentScrollInners::MODULE_SCROLLINNER_COMMENTS_LIST];

            case self::MODULE_SCROLL_COMMENTS_ADD:
                return [PoP_Module_Processor_CommentScrollInners::class, PoP_Module_Processor_CommentScrollInners::MODULE_SCROLLINNER_COMMENTS_ADD];

            case self::MODULE_SCROLLLAYOUT_POSTCOMMENT:
                return [PoP_Module_Processor_CommentScrollInners::class, PoP_Module_Processor_CommentScrollInners::MODULE_LAYOUTSCROLLINNER_POSTCOMMENTS];

            case self::MODULE_SCROLLLAYOUT_POSTCOMMENT_APPENDABLE:
                return [PoP_Module_Processor_CommentScrollInners::class, PoP_Module_Processor_CommentScrollInners::MODULE_LAYOUTSCROLLINNER_POSTCOMMENTS_APPENDABLE];
        }

        return parent::getInnerSubmodule($componentVariation);
    }

    public function addFetchedData(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_SCROLLLAYOUT_POSTCOMMENT:
            case self::MODULE_SCROLLLAYOUT_POSTCOMMENT_APPENDABLE:
                return false;
        }

        return parent::addFetchedData($componentVariation, $props);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_SCROLL_COMMENTS_LIST:
                $post_id = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                $this->appendProp($componentVariation, $props, 'class', 'pop-commentpost-'.$post_id);
                $this->appendProp($componentVariation, $props, 'class', 'pop-postcomment');
                break;

            case self::MODULE_SCROLLLAYOUT_POSTCOMMENT_APPENDABLE:
                $classes = array(
                    self::MODULE_SCROLLLAYOUT_POSTCOMMENT_APPENDABLE => 'comments',
                );

                $this->setProp($componentVariation, $props, 'appendable', true);
                $this->setProp($componentVariation, $props, 'appendable-class', $classes[$componentVariation[1]] ?? null);

                break;
        }

        parent::initModelProps($componentVariation, $props);
    }

    public function getFetchmoreButtonSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_SCROLL_COMMENTS_LIST:
            case self::MODULE_SCROLL_COMMENTS_ADD:
                return null;
        }

        return parent::getFetchmoreButtonSubmodule($componentVariation);
    }
}


