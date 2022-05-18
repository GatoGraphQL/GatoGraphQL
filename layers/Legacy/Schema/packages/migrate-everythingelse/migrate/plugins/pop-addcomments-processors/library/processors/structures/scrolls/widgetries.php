<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_CommentsWidgets extends PoP_Module_Processor_WidgetsBase
{
    public final const MODULE_WIDGET_POSTCOMMENTS = 'widget-postcomments';
    public final const MODULE_WIDGET_POSTCOMMENTS_APPENDTOSCRIPT = 'widget-postcomments-appendtoscript';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_WIDGET_POSTCOMMENTS],
            [self::class, self::MODULE_WIDGET_POSTCOMMENTS_APPENDTOSCRIPT],
        );
    }

    public function getLayoutSubmodules(array $componentVariation)
    {
        $ret = parent::getLayoutSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_WIDGET_POSTCOMMENTS:
                $ret[] = [PoP_Module_Processor_PostCommentSubcomponentLayouts::class, PoP_Module_Processor_PostCommentSubcomponentLayouts::MODULE_SUBCOMPONENT_POSTCOMMENTS];
                break;

            case self::MODULE_WIDGET_POSTCOMMENTS_APPENDTOSCRIPT:
                $ret[] = [PoP_Module_Processor_CommentsFramesLayouts::class, PoP_Module_Processor_CommentsFramesLayouts::MODULE_LAYOUT_COMMENTS_APPENDTOSCRIPT];
                break;
        }

        return $ret;
    }

    public function getMenuTitle(array $componentVariation, array &$props)
    {
        $titles = array(
            self::MODULE_WIDGET_POSTCOMMENTS => TranslationAPIFacade::getInstance()->__('Comments', 'pop-coreprocessors'),
            self::MODULE_WIDGET_POSTCOMMENTS_APPENDTOSCRIPT => TranslationAPIFacade::getInstance()->__('Comments', 'pop-coreprocessors'),
        );

        return $titles[$componentVariation[1]] ?? null;
    }
    public function getFontawesome(array $componentVariation, array &$props)
    {
        $fontawesomes = array(
            self::MODULE_WIDGET_POSTCOMMENTS => 'fa-comments',
            self::MODULE_WIDGET_POSTCOMMENTS_APPENDTOSCRIPT => 'fa-comments',
        );

        return $fontawesomes[$componentVariation[1]] ?? null;
    }
    public function getBodyClass(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_WIDGET_POSTCOMMENTS:
            case self::MODULE_WIDGET_POSTCOMMENTS_APPENDTOSCRIPT:

                return '';
        }

        return parent::getBodyClass($componentVariation, $props);
    }
    public function getItemWrapper(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_WIDGET_POSTCOMMENTS:
            case self::MODULE_WIDGET_POSTCOMMENTS_APPENDTOSCRIPT:

                return '';
        }

        return parent::getItemWrapper($componentVariation, $props);
    }
    public function getWidgetClass(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_WIDGET_POSTCOMMENTS:
            case self::MODULE_WIDGET_POSTCOMMENTS_APPENDTOSCRIPT:

                return '';
        }

        return parent::getWidgetClass($componentVariation, $props);
    }
    public function getTitleWrapperClass(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_WIDGET_POSTCOMMENTS:
            case self::MODULE_WIDGET_POSTCOMMENTS_APPENDTOSCRIPT:

                return '';
        }

        return parent::getTitleWrapperClass($componentVariation, $props);
    }
    public function getTitleClass(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_WIDGET_POSTCOMMENTS:

                return '';
        }

        return parent::getTitleClass($componentVariation, $props);
    }
    public function getQuicklinkgroupSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_WIDGET_POSTCOMMENTS:
            case self::MODULE_WIDGET_POSTCOMMENTS_APPENDTOSCRIPT:

                return [PoP_Module_Processor_AddCommentPostViewComponentButtons::class, PoP_Module_Processor_AddCommentPostViewComponentButtons::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT];
        }

        return parent::getQuicklinkgroupSubmodule($componentVariation);
    }
}


