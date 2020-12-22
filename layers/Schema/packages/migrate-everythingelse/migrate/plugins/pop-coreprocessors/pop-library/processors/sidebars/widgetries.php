<?php
use PoP\Translation\Facades\TranslationAPIFacade;

class PoP_Module_Processor_Widgets extends PoP_Module_Processor_WidgetsBase
{
    public const MODULE_WIDGET_POST_AUTHORS = 'widget-post-authors';
    public const MODULE_WIDGETCOMPACT_POST_AUTHORS = 'widgetcompact-post-authors';
    public const MODULE_WIDGETCOMPACT_AUTHORDESCRIPTION = 'widgetcompact-authordescription';
    public const MODULE_WIDGET_AUTHOR_CONTACT = 'widget-author-contact';
    public const MODULE_WIDGET_REFERENCES = 'widget-references';
    public const MODULE_WIDGET_REFERENCES_LINE = 'widget-references-line';
    public const MODULE_WIDGET_HIGHLIGHTEDPOST_LINE = 'widget-highlightedpost-line';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_WIDGET_POST_AUTHORS],
            [self::class, self::MODULE_WIDGETCOMPACT_POST_AUTHORS],
            [self::class, self::MODULE_WIDGETCOMPACT_AUTHORDESCRIPTION],
            [self::class, self::MODULE_WIDGET_AUTHOR_CONTACT],
            [self::class, self::MODULE_WIDGET_REFERENCES],
            [self::class, self::MODULE_WIDGET_REFERENCES_LINE],
            [self::class, self::MODULE_WIDGET_HIGHLIGHTEDPOST_LINE],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);
    
        switch ($module[1]) {
            case self::MODULE_WIDGET_POST_AUTHORS:
            case self::MODULE_WIDGETCOMPACT_POST_AUTHORS:
                $ret[] = [PoP_Module_Processor_PostAuthorLayouts::class, PoP_Module_Processor_PostAuthorLayouts::MODULE_LAYOUT_POSTAUTHORS];
                break;

            case self::MODULE_WIDGETCOMPACT_AUTHORDESCRIPTION:
                $ret[] = [PoP_Module_Processor_AuthorContentLayouts::class, PoP_Module_Processor_AuthorContentLayouts::MODULE_LAYOUTAUTHOR_LIMITEDCONTENT];
                break;

            case self::MODULE_WIDGET_AUTHOR_CONTACT:
                $ret[] = [PoP_Module_Processor_AuthorContactLayouts::class, PoP_Module_Processor_AuthorContactLayouts::MODULE_LAYOUT_AUTHOR_CONTACT];
                break;

            case self::MODULE_WIDGET_REFERENCES:
                $ret[] = [PoP_Module_Processor_ReferencesLayouts::class, PoP_Module_Processor_ReferencesLayouts::MODULE_LAYOUT_REFERENCES_ADDONS];
                break;

            case self::MODULE_WIDGET_REFERENCES_LINE:
                $ret[] = [PoP_Module_Processor_ReferencesLayouts::class, PoP_Module_Processor_ReferencesLayouts::MODULE_LAYOUT_REFERENCES_LINE];
                break;

            case self::MODULE_WIDGET_HIGHLIGHTEDPOST_LINE:
                $ret[] = [PoP_Module_Processor_HighlightedPostSubcomponentLayouts::class, PoP_Module_Processor_HighlightedPostSubcomponentLayouts::MODULE_LAYOUT_HIGHLIGHTEDPOST_LINE];
                break;
        }
        
        return $ret;
    }

    public function getMenuTitle(array $module, array &$props)
    {

        // $asresponse = TranslationAPIFacade::getInstance()->__('Posted in response / as an addition to', 'pop-coreprocessors');
        // $asresponse = TranslationAPIFacade::getInstance()->__('In response/addition to', 'pop-coreprocessors');
        $asresponse = TranslationAPIFacade::getInstance()->__('In response to', 'pop-coreprocessors');
        // $additionals = TranslationAPIFacade::getInstance()->__('Responses / Additionals', 'pop-coreprocessors');
        $titles = array(
            self::MODULE_WIDGET_AUTHOR_CONTACT => TranslationAPIFacade::getInstance()->__('Contact info', 'pop-coreprocessors'),
            self::MODULE_WIDGET_POST_AUTHORS => TranslationAPIFacade::getInstance()->__('Author(s)', 'pop-coreprocessors'),
            self::MODULE_WIDGETCOMPACT_AUTHORDESCRIPTION => TranslationAPIFacade::getInstance()->__('Description', 'pop-coreprocessors'),
            self::MODULE_WIDGETCOMPACT_POST_AUTHORS => TranslationAPIFacade::getInstance()->__('Author(s)', 'pop-coreprocessors'),
            self::MODULE_WIDGET_REFERENCES => $asresponse,
            self::MODULE_WIDGET_REFERENCES_LINE => $asresponse,
            self::MODULE_WIDGET_HIGHLIGHTEDPOST_LINE => TranslationAPIFacade::getInstance()->__('Highlighted from', 'pop-coreprocessors'),
        );

        return $titles[$module[1]];
    }
    public function getFontawesome(array $module, array &$props)
    {
        $fontawesomes = array(
            self::MODULE_WIDGET_AUTHOR_CONTACT => 'fa-link',
            self::MODULE_WIDGET_POST_AUTHORS => 'fa-user',
            self::MODULE_WIDGETCOMPACT_AUTHORDESCRIPTION => 'fa-circle',
            self::MODULE_WIDGETCOMPACT_POST_AUTHORS => 'fa-user',
            self::MODULE_WIDGET_REFERENCES => 'fa-asterisk',
            self::MODULE_WIDGET_REFERENCES_LINE => 'fa-asterisk',
            self::MODULE_WIDGET_HIGHLIGHTEDPOST_LINE => 'fa-circle',
        );

        return $fontawesomes[$module[1]];
    }
    public function getBodyClass(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_WIDGET_POST_AUTHORS:
                return 'list-group';

            case self::MODULE_WIDGET_REFERENCES_LINE:
            case self::MODULE_WIDGET_HIGHLIGHTEDPOST_LINE:
                return '';
        }

        return parent::getBodyClass($module, $props);
    }
    public function getItemWrapper(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_WIDGET_AUTHOR_CONTACT:
                return 'list-group-item';

            case self::MODULE_WIDGET_REFERENCES_LINE:
            case self::MODULE_WIDGET_HIGHLIGHTEDPOST_LINE:
                return '';
        }

        return parent::getItemWrapper($module, $props);
    }
    public function getWidgetClass(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_WIDGETCOMPACT_POST_AUTHORS:
            case self::MODULE_WIDGETCOMPACT_AUTHORDESCRIPTION:
                return 'panel panel-default panel-sm';

            case self::MODULE_WIDGET_REFERENCES_LINE:
            case self::MODULE_WIDGET_HIGHLIGHTEDPOST_LINE:
                // return 'panel panel-info panel-sm';
                return '';
        }

        return parent::getWidgetClass($module, $props);
    }
    public function getTitleWrapperClass(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_WIDGET_REFERENCES_LINE:
            case self::MODULE_WIDGET_HIGHLIGHTEDPOST_LINE:
                return '';
        }

        return parent::getTitleWrapperClass($module, $props);
    }
    public function getTitleClass(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_WIDGET_REFERENCES_LINE:
            case self::MODULE_WIDGET_HIGHLIGHTEDPOST_LINE:
                return '';
        }

        return parent::getTitleClass($module, $props);
    }
}


