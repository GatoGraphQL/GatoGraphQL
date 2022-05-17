<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\PostTags\ModuleConfiguration as PostTagsModuleConfiguration;

class GD_Custom_Module_Processor_TagWidgets extends PoP_Module_Processor_WidgetsBase
{
    public final const MODULE_WIDGETCOMPACT_TAGINFO = 'widgetcompact-taginfo';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_WIDGETCOMPACT_TAGINFO],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_WIDGETCOMPACT_TAGINFO:
                $ret[] = [PoP_Module_Processor_TagInfoLayouts::class, PoP_Module_Processor_TagInfoLayouts::MODULE_LAYOUT_TAGINFO];
                break;
        }

        return $ret;
    }

    public function getMenuTitle(array $module, array &$props)
    {
        $titles = array(
            self::MODULE_WIDGETCOMPACT_TAGINFO => TranslationAPIFacade::getInstance()->__('Tag/topic', 'poptheme-wassup'),
        );

        return $titles[$module[1]] ?? null;
    }
    public function getFontawesome(array $module, array &$props)
    {
        $fontawesomes = array(
            self::MODULE_WIDGETCOMPACT_TAGINFO => getRouteIcon(PostTagsModuleConfiguration::getPostTagsRoute(), false),
        );

        return $fontawesomes[$module[1]] ?? null;
    }

    public function getBodyClass(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_WIDGETCOMPACT_TAGINFO:
                return 'list-group list-group-sm';
        }

        return parent::getBodyClass($module, $props);
    }
    public function getItemWrapper(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_WIDGETCOMPACT_TAGINFO:
                return 'pop-hide-empty list-group-item';
        }

        return parent::getItemWrapper($module, $props);
    }
    public function getWidgetClass(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_WIDGETCOMPACT_TAGINFO:
                // return 'panel panel-info panel-sm';
                return 'panel panel-default panel-sm';
        }

        return parent::getWidgetClass($module, $props);
    }
}



