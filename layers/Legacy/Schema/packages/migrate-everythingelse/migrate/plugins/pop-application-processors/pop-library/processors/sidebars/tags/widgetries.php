<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\PostTags\ModuleConfiguration as PostTagsModuleConfiguration;

class GD_Custom_Module_Processor_TagWidgets extends PoP_Module_Processor_WidgetsBase
{
    public final const COMPONENT_WIDGETCOMPACT_TAGINFO = 'widgetcompact-taginfo';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_WIDGETCOMPACT_TAGINFO],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_WIDGETCOMPACT_TAGINFO:
                $ret[] = [PoP_Module_Processor_TagInfoLayouts::class, PoP_Module_Processor_TagInfoLayouts::COMPONENT_LAYOUT_TAGINFO];
                break;
        }

        return $ret;
    }

    public function getMenuTitle(array $component, array &$props)
    {
        $titles = array(
            self::COMPONENT_WIDGETCOMPACT_TAGINFO => TranslationAPIFacade::getInstance()->__('Tag/topic', 'poptheme-wassup'),
        );

        return $titles[$component[1]] ?? null;
    }
    public function getFontawesome(array $component, array &$props)
    {
        $fontawesomes = array(
            self::COMPONENT_WIDGETCOMPACT_TAGINFO => getRouteIcon(PostTagsModuleConfiguration::getPostTagsRoute(), false),
        );

        return $fontawesomes[$component[1]] ?? null;
    }

    public function getBodyClass(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_WIDGETCOMPACT_TAGINFO:
                return 'list-group list-group-sm';
        }

        return parent::getBodyClass($component, $props);
    }
    public function getItemWrapper(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_WIDGETCOMPACT_TAGINFO:
                return 'pop-hide-empty list-group-item';
        }

        return parent::getItemWrapper($component, $props);
    }
    public function getWidgetClass(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_WIDGETCOMPACT_TAGINFO:
                // return 'panel panel-info panel-sm';
                return 'panel panel-default panel-sm';
        }

        return parent::getWidgetClass($component, $props);
    }
}



