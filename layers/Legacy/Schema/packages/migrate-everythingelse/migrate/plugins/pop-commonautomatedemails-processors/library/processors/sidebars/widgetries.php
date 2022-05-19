<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoPTheme_Wassup_AE_Module_Processor_Widgets extends PoP_Module_Processor_WidgetsBase
{
    public final const COMPONENT_WIDGETCOMPACT_AUTOMATEDEMAILS_POST_AUTHORS = 'widgetcompact-automatedemails-post-authors';
    public final const COMPONENT_WIDGETCOMPACT_AUTOMATEDEMAILS_POSTINFO = 'widgetcompact-automatedemails-postinfo';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_WIDGETCOMPACT_AUTOMATEDEMAILS_POST_AUTHORS],
            [self::class, self::COMPONENT_WIDGETCOMPACT_AUTOMATEDEMAILS_POSTINFO],
        );
    }

    public function getLayoutSubcomponents(array $component)
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_WIDGETCOMPACT_AUTOMATEDEMAILS_POST_AUTHORS:
                $ret[] = [PoPTheme_Wassup_AE_Module_Processor_PostAuthorLayouts::class, PoPTheme_Wassup_AE_Module_Processor_PostAuthorLayouts::COMPONENT_LAYOUT_AUTOMATEDEMAILS_POSTAUTHORS];
                break;

            case self::COMPONENT_WIDGETCOMPACT_AUTOMATEDEMAILS_POSTINFO:
                if (PoP_ApplicationProcessors_Utils::addCategories()) {
                    $ret[] = [Wassup_Module_Processor_WidgetWrappers::class, Wassup_Module_Processor_WidgetWrappers::COMPONENT_LAYOUTWRAPPER_CATEGORIES];
                }
                if (PoP_ApplicationProcessors_Utils::addAppliesto()) {
                    $ret[] = [Wassup_Module_Processor_WidgetWrappers::class, Wassup_Module_Processor_WidgetWrappers::COMPONENT_LAYOUTWRAPPER_APPLIESTO];
                }
                $ret[] = [PoP_Module_Processor_PublishedLayouts::class, PoP_Module_Processor_PublishedLayouts::COMPONENT_LAYOUT_PUBLISHED];
                $ret[] = [PoP_Module_Processor_QuicklinkButtonGroups::class, PoP_Module_Processor_QuicklinkButtonGroups::COMPONENT_QUICKLINKBUTTONGROUP_COMMENTS_LABEL];
                break;
        }

        return $ret;
    }

    public function getMenuTitle(array $component, array &$props)
    {
        $titles = array(
            self::COMPONENT_WIDGETCOMPACT_AUTOMATEDEMAILS_POST_AUTHORS => TranslationAPIFacade::getInstance()->__('Author(s)', 'pop-coreprocessors'),
            self::COMPONENT_WIDGETCOMPACT_AUTOMATEDEMAILS_POSTINFO => TranslationAPIFacade::getInstance()->__('Post', 'poptheme-wassup'),
        );

        return $titles[$component[1]] ?? null;
    }
    public function getFontawesome(array $component, array &$props)
    {
        $fontawesomes = array(
            self::COMPONENT_WIDGETCOMPACT_AUTOMATEDEMAILS_POST_AUTHORS => 'fa-user',
            self::COMPONENT_WIDGETCOMPACT_AUTOMATEDEMAILS_POSTINFO => 'fa-circle',
        );

        return $fontawesomes[$component[1]] ?? null;
    }
    public function getBodyClass(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_WIDGETCOMPACT_AUTOMATEDEMAILS_POSTINFO:
                return 'list-group list-group-sm';
        }

        return parent::getBodyClass($component, $props);
    }
    public function getItemWrapper(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_WIDGETCOMPACT_AUTOMATEDEMAILS_POSTINFO:
                return 'pop-hide-empty list-group-item';
        }

        return parent::getItemWrapper($component, $props);
    }
    public function getWidgetClass(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_WIDGETCOMPACT_AUTOMATEDEMAILS_POST_AUTHORS:
            case self::COMPONENT_WIDGETCOMPACT_AUTOMATEDEMAILS_POSTINFO:
                return 'panel panel-default panel-sm';
        }

        return parent::getWidgetClass($component, $props);
    }
}


