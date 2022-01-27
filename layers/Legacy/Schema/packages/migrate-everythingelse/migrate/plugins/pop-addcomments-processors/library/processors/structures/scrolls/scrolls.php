<?php

use PoP\ComponentModel\State\ApplicationState;

class PoP_Module_Processor_CommentScrolls extends PoP_Module_Processor_ScrollsBase
{
    public const MODULE_SCROLL_COMMENTS_LIST = 'scroll-comments-list';
    public const MODULE_SCROLL_COMMENTS_ADD = 'scroll-comments-add';
    public const MODULE_SCROLLLAYOUT_POSTCOMMENT = 'layout-postcomment-scroll';
    public const MODULE_SCROLLLAYOUT_POSTCOMMENT_APPENDABLE = 'layout-postcomment-scroll-appendable';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLL_COMMENTS_LIST],
            [self::class, self::MODULE_SCROLL_COMMENTS_ADD],
            [self::class, self::MODULE_SCROLLLAYOUT_POSTCOMMENT],
            [self::class, self::MODULE_SCROLLLAYOUT_POSTCOMMENT_APPENDABLE],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_SCROLL_COMMENTS_LIST:
                return [PoP_Module_Processor_CommentScrollInners::class, PoP_Module_Processor_CommentScrollInners::MODULE_SCROLLINNER_COMMENTS_LIST];

            case self::MODULE_SCROLL_COMMENTS_ADD:
                return [PoP_Module_Processor_CommentScrollInners::class, PoP_Module_Processor_CommentScrollInners::MODULE_SCROLLINNER_COMMENTS_ADD];

            case self::MODULE_SCROLLLAYOUT_POSTCOMMENT:
                return [PoP_Module_Processor_CommentScrollInners::class, PoP_Module_Processor_CommentScrollInners::MODULE_LAYOUTSCROLLINNER_POSTCOMMENTS];

            case self::MODULE_SCROLLLAYOUT_POSTCOMMENT_APPENDABLE:
                return [PoP_Module_Processor_CommentScrollInners::class, PoP_Module_Processor_CommentScrollInners::MODULE_LAYOUTSCROLLINNER_POSTCOMMENTS_APPENDABLE];
        }

        return parent::getInnerSubmodule($module);
    }

    public function addFetchedData(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_SCROLLLAYOUT_POSTCOMMENT:
            case self::MODULE_SCROLLLAYOUT_POSTCOMMENT_APPENDABLE:
                return false;
        }

        return parent::addFetchedData($module, $props);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_SCROLL_COMMENTS_LIST:
                $post_id = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                $this->appendProp($module, $props, 'class', 'pop-commentpost-'.$post_id);
                $this->appendProp($module, $props, 'class', 'pop-postcomment');
                break;

            case self::MODULE_SCROLLLAYOUT_POSTCOMMENT_APPENDABLE:
                $classes = array(
                    self::MODULE_SCROLLLAYOUT_POSTCOMMENT_APPENDABLE => 'comments',
                );

                $this->setProp($module, $props, 'appendable', true);
                $this->setProp($module, $props, 'appendable-class', $classes[$module[1]] ?? null);

                break;
        }

        parent::initModelProps($module, $props);
    }

    public function getFetchmoreButtonSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_SCROLL_COMMENTS_LIST:
            case self::MODULE_SCROLL_COMMENTS_ADD:
                return null;
        }

        return parent::getFetchmoreButtonSubmodule($module);
    }
}


