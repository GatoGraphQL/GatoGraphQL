<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\PostTags\ModuleConfiguration as PostTagsModuleConfiguration;

class GD_Custom_Module_Processor_TagWidgets extends PoP_Module_Processor_WidgetsBase
{
    public final const COMPONENT_WIDGETCOMPACT_TAGINFO = 'widgetcompact-taginfo';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_WIDGETCOMPACT_TAGINFO,
        );
    }

    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_WIDGETCOMPACT_TAGINFO:
                $ret[] = [PoP_Module_Processor_TagInfoLayouts::class, PoP_Module_Processor_TagInfoLayouts::COMPONENT_LAYOUT_TAGINFO];
                break;
        }

        return $ret;
    }

    public function getMenuTitle(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $titles = array(
            self::COMPONENT_WIDGETCOMPACT_TAGINFO => TranslationAPIFacade::getInstance()->__('Tag/topic', 'poptheme-wassup'),
        );

        return $titles[$component->name] ?? null;
    }
    public function getFontawesome(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $fontawesomes = array(
            self::COMPONENT_WIDGETCOMPACT_TAGINFO => getRouteIcon(PostTagsModuleConfiguration::getPostTagsRoute(), false),
        );

        return $fontawesomes[$component->name] ?? null;
    }

    public function getBodyClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_WIDGETCOMPACT_TAGINFO:
                return 'list-group list-group-sm';
        }

        return parent::getBodyClass($component, $props);
    }
    public function getItemWrapper(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_WIDGETCOMPACT_TAGINFO:
                return 'pop-hide-empty list-group-item';
        }

        return parent::getItemWrapper($component, $props);
    }
    public function getWidgetClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_WIDGETCOMPACT_TAGINFO:
                // return 'panel panel-info panel-sm';
                return 'panel panel-default panel-sm';
        }

        return parent::getWidgetClass($component, $props);
    }
}



