<?php

use PoP\ComponentModel\State\ApplicationState;

class PoP_Module_Processor_CommentScrolls extends PoP_Module_Processor_ScrollsBase
{
    public final const COMPONENT_SCROLL_COMMENTS_LIST = 'scroll-comments-list';
    public final const COMPONENT_SCROLL_COMMENTS_ADD = 'scroll-comments-add';
    public final const COMPONENT_SCROLLLAYOUT_POSTCOMMENT = 'layout-postcomment-scroll';
    public final const COMPONENT_SCROLLLAYOUT_POSTCOMMENT_APPENDABLE = 'layout-postcomment-scroll-appendable';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_SCROLL_COMMENTS_LIST,
            self::COMPONENT_SCROLL_COMMENTS_ADD,
            self::COMPONENT_SCROLLLAYOUT_POSTCOMMENT,
            self::COMPONENT_SCROLLLAYOUT_POSTCOMMENT_APPENDABLE,
        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_SCROLL_COMMENTS_LIST:
                return [PoP_Module_Processor_CommentScrollInners::class, PoP_Module_Processor_CommentScrollInners::COMPONENT_SCROLLINNER_COMMENTS_LIST];

            case self::COMPONENT_SCROLL_COMMENTS_ADD:
                return [PoP_Module_Processor_CommentScrollInners::class, PoP_Module_Processor_CommentScrollInners::COMPONENT_SCROLLINNER_COMMENTS_ADD];

            case self::COMPONENT_SCROLLLAYOUT_POSTCOMMENT:
                return [PoP_Module_Processor_CommentScrollInners::class, PoP_Module_Processor_CommentScrollInners::COMPONENT_LAYOUTSCROLLINNER_POSTCOMMENTS];

            case self::COMPONENT_SCROLLLAYOUT_POSTCOMMENT_APPENDABLE:
                return [PoP_Module_Processor_CommentScrollInners::class, PoP_Module_Processor_CommentScrollInners::COMPONENT_LAYOUTSCROLLINNER_POSTCOMMENTS_APPENDABLE];
        }

        return parent::getInnerSubcomponent($component);
    }

    public function addFetchedData(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_SCROLLLAYOUT_POSTCOMMENT:
            case self::COMPONENT_SCROLLLAYOUT_POSTCOMMENT_APPENDABLE:
                return false;
        }

        return parent::addFetchedData($component, $props);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_SCROLL_COMMENTS_LIST:
                $post_id = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                $this->appendProp($component, $props, 'class', 'pop-commentpost-'.$post_id);
                $this->appendProp($component, $props, 'class', 'pop-postcomment');
                break;

            case self::COMPONENT_SCROLLLAYOUT_POSTCOMMENT_APPENDABLE:
                $classes = array(
                    self::COMPONENT_SCROLLLAYOUT_POSTCOMMENT_APPENDABLE => 'comments',
                );

                $this->setProp($component, $props, 'appendable', true);
                $this->setProp($component, $props, 'appendable-class', $classes[$component->name] ?? null);

                break;
        }

        parent::initModelProps($component, $props);
    }

    public function getFetchmoreButtonSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_SCROLL_COMMENTS_LIST:
            case self::COMPONENT_SCROLL_COMMENTS_ADD:
                return null;
        }

        return parent::getFetchmoreButtonSubcomponent($component);
    }
}


