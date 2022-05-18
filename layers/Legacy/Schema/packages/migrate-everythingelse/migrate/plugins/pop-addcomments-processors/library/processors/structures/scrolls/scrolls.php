<?php

use PoP\ComponentModel\State\ApplicationState;

class PoP_Module_Processor_CommentScrolls extends PoP_Module_Processor_ScrollsBase
{
    public final const MODULE_SCROLL_COMMENTS_LIST = 'scroll-comments-list';
    public final const MODULE_SCROLL_COMMENTS_ADD = 'scroll-comments-add';
    public final const MODULE_SCROLLLAYOUT_POSTCOMMENT = 'layout-postcomment-scroll';
    public final const MODULE_SCROLLLAYOUT_POSTCOMMENT_APPENDABLE = 'layout-postcomment-scroll-appendable';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLL_COMMENTS_LIST],
            [self::class, self::MODULE_SCROLL_COMMENTS_ADD],
            [self::class, self::MODULE_SCROLLLAYOUT_POSTCOMMENT],
            [self::class, self::MODULE_SCROLLLAYOUT_POSTCOMMENT_APPENDABLE],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_SCROLL_COMMENTS_LIST:
                return [PoP_Module_Processor_CommentScrollInners::class, PoP_Module_Processor_CommentScrollInners::MODULE_SCROLLINNER_COMMENTS_LIST];

            case self::MODULE_SCROLL_COMMENTS_ADD:
                return [PoP_Module_Processor_CommentScrollInners::class, PoP_Module_Processor_CommentScrollInners::MODULE_SCROLLINNER_COMMENTS_ADD];

            case self::MODULE_SCROLLLAYOUT_POSTCOMMENT:
                return [PoP_Module_Processor_CommentScrollInners::class, PoP_Module_Processor_CommentScrollInners::MODULE_LAYOUTSCROLLINNER_POSTCOMMENTS];

            case self::MODULE_SCROLLLAYOUT_POSTCOMMENT_APPENDABLE:
                return [PoP_Module_Processor_CommentScrollInners::class, PoP_Module_Processor_CommentScrollInners::MODULE_LAYOUTSCROLLINNER_POSTCOMMENTS_APPENDABLE];
        }

        return parent::getInnerSubmodule($component);
    }

    public function addFetchedData(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_SCROLLLAYOUT_POSTCOMMENT:
            case self::MODULE_SCROLLLAYOUT_POSTCOMMENT_APPENDABLE:
                return false;
        }

        return parent::addFetchedData($component, $props);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::MODULE_SCROLL_COMMENTS_LIST:
                $post_id = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                $this->appendProp($component, $props, 'class', 'pop-commentpost-'.$post_id);
                $this->appendProp($component, $props, 'class', 'pop-postcomment');
                break;

            case self::MODULE_SCROLLLAYOUT_POSTCOMMENT_APPENDABLE:
                $classes = array(
                    self::MODULE_SCROLLLAYOUT_POSTCOMMENT_APPENDABLE => 'comments',
                );

                $this->setProp($component, $props, 'appendable', true);
                $this->setProp($component, $props, 'appendable-class', $classes[$component[1]] ?? null);

                break;
        }

        parent::initModelProps($component, $props);
    }

    public function getFetchmoreButtonSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_SCROLL_COMMENTS_LIST:
            case self::MODULE_SCROLL_COMMENTS_ADD:
                return null;
        }

        return parent::getFetchmoreButtonSubmodule($component);
    }
}


