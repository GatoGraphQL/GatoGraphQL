<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_Widgets extends PoP_Module_Processor_WidgetsBase
{
    public final const MODULE_WIDGET_POST_AUTHORS = 'widget-post-authors';
    public final const MODULE_WIDGETCOMPACT_POST_AUTHORS = 'widgetcompact-post-authors';
    public final const MODULE_WIDGETCOMPACT_AUTHORDESCRIPTION = 'widgetcompact-authordescription';
    public final const MODULE_WIDGET_AUTHOR_CONTACT = 'widget-author-contact';
    public final const MODULE_WIDGET_REFERENCES = 'widget-references';
    public final const MODULE_WIDGET_REFERENCES_LINE = 'widget-references-line';
    public final const MODULE_WIDGET_HIGHLIGHTEDPOST_LINE = 'widget-highlightedpost-line';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_WIDGET_POST_AUTHORS],
            [self::class, self::COMPONENT_WIDGETCOMPACT_POST_AUTHORS],
            [self::class, self::COMPONENT_WIDGETCOMPACT_AUTHORDESCRIPTION],
            [self::class, self::COMPONENT_WIDGET_AUTHOR_CONTACT],
            [self::class, self::COMPONENT_WIDGET_REFERENCES],
            [self::class, self::COMPONENT_WIDGET_REFERENCES_LINE],
            [self::class, self::COMPONENT_WIDGET_HIGHLIGHTEDPOST_LINE],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_WIDGET_POST_AUTHORS:
            case self::COMPONENT_WIDGETCOMPACT_POST_AUTHORS:
                $ret[] = [PoP_Module_Processor_PostAuthorLayouts::class, PoP_Module_Processor_PostAuthorLayouts::COMPONENT_LAYOUT_POSTAUTHORS];
                break;

            case self::COMPONENT_WIDGETCOMPACT_AUTHORDESCRIPTION:
                $ret[] = [PoP_Module_Processor_AuthorContentLayouts::class, PoP_Module_Processor_AuthorContentLayouts::COMPONENT_LAYOUTAUTHOR_LIMITEDCONTENT];
                break;

            case self::COMPONENT_WIDGET_AUTHOR_CONTACT:
                $ret[] = [PoP_Module_Processor_AuthorContactLayouts::class, PoP_Module_Processor_AuthorContactLayouts::COMPONENT_LAYOUT_AUTHOR_CONTACT];
                break;

            case self::COMPONENT_WIDGET_REFERENCES:
                $ret[] = [PoP_Module_Processor_ReferencesLayouts::class, PoP_Module_Processor_ReferencesLayouts::COMPONENT_LAYOUT_REFERENCES_ADDONS];
                break;

            case self::COMPONENT_WIDGET_REFERENCES_LINE:
                $ret[] = [PoP_Module_Processor_ReferencesLayouts::class, PoP_Module_Processor_ReferencesLayouts::COMPONENT_LAYOUT_REFERENCES_LINE];
                break;

            case self::COMPONENT_WIDGET_HIGHLIGHTEDPOST_LINE:
                $ret[] = [PoP_Module_Processor_HighlightedPostSubcomponentLayouts::class, PoP_Module_Processor_HighlightedPostSubcomponentLayouts::COMPONENT_LAYOUT_HIGHLIGHTEDPOST_LINE];
                break;
        }

        return $ret;
    }

    public function getMenuTitle(array $component, array &$props)
    {

        // $asresponse = TranslationAPIFacade::getInstance()->__('Posted in response / as an addition to', 'pop-coreprocessors');
        // $asresponse = TranslationAPIFacade::getInstance()->__('In response/addition to', 'pop-coreprocessors');
        $asresponse = TranslationAPIFacade::getInstance()->__('In response to', 'pop-coreprocessors');
        // $additionals = TranslationAPIFacade::getInstance()->__('Responses / Additionals', 'pop-coreprocessors');
        $titles = array(
            self::COMPONENT_WIDGET_AUTHOR_CONTACT => TranslationAPIFacade::getInstance()->__('Contact info', 'pop-coreprocessors'),
            self::COMPONENT_WIDGET_POST_AUTHORS => TranslationAPIFacade::getInstance()->__('Author(s)', 'pop-coreprocessors'),
            self::COMPONENT_WIDGETCOMPACT_AUTHORDESCRIPTION => TranslationAPIFacade::getInstance()->__('Description', 'pop-coreprocessors'),
            self::COMPONENT_WIDGETCOMPACT_POST_AUTHORS => TranslationAPIFacade::getInstance()->__('Author(s)', 'pop-coreprocessors'),
            self::COMPONENT_WIDGET_REFERENCES => $asresponse,
            self::COMPONENT_WIDGET_REFERENCES_LINE => $asresponse,
            self::COMPONENT_WIDGET_HIGHLIGHTEDPOST_LINE => TranslationAPIFacade::getInstance()->__('Highlighted from', 'pop-coreprocessors'),
        );

        return $titles[$component[1]] ?? null;
    }
    public function getFontawesome(array $component, array &$props)
    {
        $fontawesomes = array(
            self::COMPONENT_WIDGET_AUTHOR_CONTACT => 'fa-link',
            self::COMPONENT_WIDGET_POST_AUTHORS => 'fa-user',
            self::COMPONENT_WIDGETCOMPACT_AUTHORDESCRIPTION => 'fa-circle',
            self::COMPONENT_WIDGETCOMPACT_POST_AUTHORS => 'fa-user',
            self::COMPONENT_WIDGET_REFERENCES => 'fa-asterisk',
            self::COMPONENT_WIDGET_REFERENCES_LINE => 'fa-asterisk',
            self::COMPONENT_WIDGET_HIGHLIGHTEDPOST_LINE => 'fa-circle',
        );

        return $fontawesomes[$component[1]] ?? null;
    }
    public function getBodyClass(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_WIDGET_POST_AUTHORS:
                return 'list-group';

            case self::COMPONENT_WIDGET_REFERENCES_LINE:
            case self::COMPONENT_WIDGET_HIGHLIGHTEDPOST_LINE:
                return '';
        }

        return parent::getBodyClass($component, $props);
    }
    public function getItemWrapper(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_WIDGET_AUTHOR_CONTACT:
                return 'list-group-item';

            case self::COMPONENT_WIDGET_REFERENCES_LINE:
            case self::COMPONENT_WIDGET_HIGHLIGHTEDPOST_LINE:
                return '';
        }

        return parent::getItemWrapper($component, $props);
    }
    public function getWidgetClass(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_WIDGETCOMPACT_POST_AUTHORS:
            case self::COMPONENT_WIDGETCOMPACT_AUTHORDESCRIPTION:
                return 'panel panel-default panel-sm';

            case self::COMPONENT_WIDGET_REFERENCES_LINE:
            case self::COMPONENT_WIDGET_HIGHLIGHTEDPOST_LINE:
                // return 'panel panel-info panel-sm';
                return '';
        }

        return parent::getWidgetClass($component, $props);
    }
    public function getTitleWrapperClass(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_WIDGET_REFERENCES_LINE:
            case self::COMPONENT_WIDGET_HIGHLIGHTEDPOST_LINE:
                return '';
        }

        return parent::getTitleWrapperClass($component, $props);
    }
    public function getTitleClass(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_WIDGET_REFERENCES_LINE:
            case self::COMPONENT_WIDGET_HIGHLIGHTEDPOST_LINE:
                return '';
        }

        return parent::getTitleClass($component, $props);
    }
}


