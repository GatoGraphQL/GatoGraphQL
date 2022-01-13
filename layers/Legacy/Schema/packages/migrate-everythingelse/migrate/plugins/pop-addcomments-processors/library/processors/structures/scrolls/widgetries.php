<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_CommentsWidgets extends PoP_Module_Processor_WidgetsBase
{
    public const MODULE_WIDGET_POSTCOMMENTS = 'widget-postcomments';
    public const MODULE_WIDGET_POSTCOMMENTS_APPENDTOSCRIPT = 'widget-postcomments-appendtoscript';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_WIDGET_POSTCOMMENTS],
            [self::class, self::MODULE_WIDGET_POSTCOMMENTS_APPENDTOSCRIPT],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_WIDGET_POSTCOMMENTS:
                $ret[] = [PoP_Module_Processor_PostCommentSubcomponentLayouts::class, PoP_Module_Processor_PostCommentSubcomponentLayouts::MODULE_SUBCOMPONENT_POSTCOMMENTS];
                break;

            case self::MODULE_WIDGET_POSTCOMMENTS_APPENDTOSCRIPT:
                $ret[] = [PoP_Module_Processor_CommentsFramesLayouts::class, PoP_Module_Processor_CommentsFramesLayouts::MODULE_LAYOUT_COMMENTS_APPENDTOSCRIPT];
                break;
        }

        return $ret;
    }

    public function getMenuTitle(array $module, array &$props)
    {
        $titles = array(
            self::MODULE_WIDGET_POSTCOMMENTS => TranslationAPIFacade::getInstance()->__('Comments', 'pop-coreprocessors'),
            self::MODULE_WIDGET_POSTCOMMENTS_APPENDTOSCRIPT => TranslationAPIFacade::getInstance()->__('Comments', 'pop-coreprocessors'),
        );

        return $titles[$module[1]] ?? null;
    }
    public function getFontawesome(array $module, array &$props)
    {
        $fontawesomes = array(
            self::MODULE_WIDGET_POSTCOMMENTS => 'fa-comments',
            self::MODULE_WIDGET_POSTCOMMENTS_APPENDTOSCRIPT => 'fa-comments',
        );

        return $fontawesomes[$module[1]] ?? null;
    }
    public function getBodyClass(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_WIDGET_POSTCOMMENTS:
            case self::MODULE_WIDGET_POSTCOMMENTS_APPENDTOSCRIPT:

                return '';
        }

        return parent::getBodyClass($module, $props);
    }
    public function getItemWrapper(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_WIDGET_POSTCOMMENTS:
            case self::MODULE_WIDGET_POSTCOMMENTS_APPENDTOSCRIPT:

                return '';
        }

        return parent::getItemWrapper($module, $props);
    }
    public function getWidgetClass(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_WIDGET_POSTCOMMENTS:
            case self::MODULE_WIDGET_POSTCOMMENTS_APPENDTOSCRIPT:

                return '';
        }

        return parent::getWidgetClass($module, $props);
    }
    public function getTitleWrapperClass(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_WIDGET_POSTCOMMENTS:
            case self::MODULE_WIDGET_POSTCOMMENTS_APPENDTOSCRIPT:

                return '';
        }

        return parent::getTitleWrapperClass($module, $props);
    }
    public function getTitleClass(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_WIDGET_POSTCOMMENTS:

                return '';
        }

        return parent::getTitleClass($module, $props);
    }
    public function getQuicklinkgroupSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_WIDGET_POSTCOMMENTS:
            case self::MODULE_WIDGET_POSTCOMMENTS_APPENDTOSCRIPT:

                return [PoP_Module_Processor_AddCommentPostViewComponentButtons::class, PoP_Module_Processor_AddCommentPostViewComponentButtons::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT];
        }

        return parent::getQuicklinkgroupSubmodule($module);
    }
}


