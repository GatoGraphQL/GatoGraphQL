<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoPTheme_Wassup_AE_Module_Processor_Widgets extends PoP_Module_Processor_WidgetsBase
{
    public final const MODULE_WIDGETCOMPACT_AUTOMATEDEMAILS_POST_AUTHORS = 'widgetcompact-automatedemails-post-authors';
    public final const MODULE_WIDGETCOMPACT_AUTOMATEDEMAILS_POSTINFO = 'widgetcompact-automatedemails-postinfo';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_WIDGETCOMPACT_AUTOMATEDEMAILS_POST_AUTHORS],
            [self::class, self::MODULE_WIDGETCOMPACT_AUTOMATEDEMAILS_POSTINFO],
        );
    }

    public function getLayoutSubmodules(array $componentVariation)
    {
        $ret = parent::getLayoutSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_WIDGETCOMPACT_AUTOMATEDEMAILS_POST_AUTHORS:
                $ret[] = [PoPTheme_Wassup_AE_Module_Processor_PostAuthorLayouts::class, PoPTheme_Wassup_AE_Module_Processor_PostAuthorLayouts::MODULE_LAYOUT_AUTOMATEDEMAILS_POSTAUTHORS];
                break;

            case self::MODULE_WIDGETCOMPACT_AUTOMATEDEMAILS_POSTINFO:
                if (PoP_ApplicationProcessors_Utils::addCategories()) {
                    $ret[] = [Wassup_Module_Processor_WidgetWrappers::class, Wassup_Module_Processor_WidgetWrappers::MODULE_LAYOUTWRAPPER_CATEGORIES];
                }
                if (PoP_ApplicationProcessors_Utils::addAppliesto()) {
                    $ret[] = [Wassup_Module_Processor_WidgetWrappers::class, Wassup_Module_Processor_WidgetWrappers::MODULE_LAYOUTWRAPPER_APPLIESTO];
                }
                $ret[] = [PoP_Module_Processor_PublishedLayouts::class, PoP_Module_Processor_PublishedLayouts::MODULE_LAYOUT_PUBLISHED];
                $ret[] = [PoP_Module_Processor_QuicklinkButtonGroups::class, PoP_Module_Processor_QuicklinkButtonGroups::MODULE_QUICKLINKBUTTONGROUP_COMMENTS_LABEL];
                break;
        }

        return $ret;
    }

    public function getMenuTitle(array $componentVariation, array &$props)
    {
        $titles = array(
            self::MODULE_WIDGETCOMPACT_AUTOMATEDEMAILS_POST_AUTHORS => TranslationAPIFacade::getInstance()->__('Author(s)', 'pop-coreprocessors'),
            self::MODULE_WIDGETCOMPACT_AUTOMATEDEMAILS_POSTINFO => TranslationAPIFacade::getInstance()->__('Post', 'poptheme-wassup'),
        );

        return $titles[$componentVariation[1]] ?? null;
    }
    public function getFontawesome(array $componentVariation, array &$props)
    {
        $fontawesomes = array(
            self::MODULE_WIDGETCOMPACT_AUTOMATEDEMAILS_POST_AUTHORS => 'fa-user',
            self::MODULE_WIDGETCOMPACT_AUTOMATEDEMAILS_POSTINFO => 'fa-circle',
        );

        return $fontawesomes[$componentVariation[1]] ?? null;
    }
    public function getBodyClass(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_WIDGETCOMPACT_AUTOMATEDEMAILS_POSTINFO:
                return 'list-group list-group-sm';
        }

        return parent::getBodyClass($componentVariation, $props);
    }
    public function getItemWrapper(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_WIDGETCOMPACT_AUTOMATEDEMAILS_POSTINFO:
                return 'pop-hide-empty list-group-item';
        }

        return parent::getItemWrapper($componentVariation, $props);
    }
    public function getWidgetClass(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_WIDGETCOMPACT_AUTOMATEDEMAILS_POST_AUTHORS:
            case self::MODULE_WIDGETCOMPACT_AUTOMATEDEMAILS_POSTINFO:
                return 'panel panel-default panel-sm';
        }

        return parent::getWidgetClass($componentVariation, $props);
    }
}


