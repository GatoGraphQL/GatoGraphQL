<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_CommentsWidgets extends PoP_Module_Processor_WidgetsBase
{
    public final const COMPONENT_WIDGET_POSTCOMMENTS = 'widget-postcomments';
    public final const COMPONENT_WIDGET_POSTCOMMENTS_APPENDTOSCRIPT = 'widget-postcomments-appendtoscript';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_WIDGET_POSTCOMMENTS],
            [self::class, self::COMPONENT_WIDGET_POSTCOMMENTS_APPENDTOSCRIPT],
        );
    }

    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_WIDGET_POSTCOMMENTS:
                $ret[] = [PoP_Module_Processor_PostCommentSubcomponentLayouts::class, PoP_Module_Processor_PostCommentSubcomponentLayouts::COMPONENT_SUBCOMPONENT_POSTCOMMENTS];
                break;

            case self::COMPONENT_WIDGET_POSTCOMMENTS_APPENDTOSCRIPT:
                $ret[] = [PoP_Module_Processor_CommentsFramesLayouts::class, PoP_Module_Processor_CommentsFramesLayouts::COMPONENT_LAYOUT_COMMENTS_APPENDTOSCRIPT];
                break;
        }

        return $ret;
    }

    public function getMenuTitle(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $titles = array(
            self::COMPONENT_WIDGET_POSTCOMMENTS => TranslationAPIFacade::getInstance()->__('Comments', 'pop-coreprocessors'),
            self::COMPONENT_WIDGET_POSTCOMMENTS_APPENDTOSCRIPT => TranslationAPIFacade::getInstance()->__('Comments', 'pop-coreprocessors'),
        );

        return $titles[$component[1]] ?? null;
    }
    public function getFontawesome(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $fontawesomes = array(
            self::COMPONENT_WIDGET_POSTCOMMENTS => 'fa-comments',
            self::COMPONENT_WIDGET_POSTCOMMENTS_APPENDTOSCRIPT => 'fa-comments',
        );

        return $fontawesomes[$component[1]] ?? null;
    }
    public function getBodyClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_WIDGET_POSTCOMMENTS:
            case self::COMPONENT_WIDGET_POSTCOMMENTS_APPENDTOSCRIPT:

                return '';
        }

        return parent::getBodyClass($component, $props);
    }
    public function getItemWrapper(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_WIDGET_POSTCOMMENTS:
            case self::COMPONENT_WIDGET_POSTCOMMENTS_APPENDTOSCRIPT:

                return '';
        }

        return parent::getItemWrapper($component, $props);
    }
    public function getWidgetClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_WIDGET_POSTCOMMENTS:
            case self::COMPONENT_WIDGET_POSTCOMMENTS_APPENDTOSCRIPT:

                return '';
        }

        return parent::getWidgetClass($component, $props);
    }
    public function getTitleWrapperClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_WIDGET_POSTCOMMENTS:
            case self::COMPONENT_WIDGET_POSTCOMMENTS_APPENDTOSCRIPT:

                return '';
        }

        return parent::getTitleWrapperClass($component, $props);
    }
    public function getTitleClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_WIDGET_POSTCOMMENTS:

                return '';
        }

        return parent::getTitleClass($component, $props);
    }
    public function getQuicklinkgroupSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_WIDGET_POSTCOMMENTS:
            case self::COMPONENT_WIDGET_POSTCOMMENTS_APPENDTOSCRIPT:

                return [PoP_Module_Processor_AddCommentPostViewComponentButtons::class, PoP_Module_Processor_AddCommentPostViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT];
        }

        return parent::getQuicklinkgroupSubcomponent($component);
    }
}


