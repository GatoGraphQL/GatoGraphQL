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

    public function getComponentVariationsToProcess(): array
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

    public function getLayoutSubmodules(array $componentVariation)
    {
        $ret = parent::getLayoutSubmodules($componentVariation);

        switch ($componentVariation[1]) {
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

    public function getMenuTitle(array $componentVariation, array &$props)
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

        return $titles[$componentVariation[1]] ?? null;
    }
    public function getFontawesome(array $componentVariation, array &$props)
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

        return $fontawesomes[$componentVariation[1]] ?? null;
    }
    public function getBodyClass(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_WIDGET_POST_AUTHORS:
                return 'list-group';

            case self::MODULE_WIDGET_REFERENCES_LINE:
            case self::MODULE_WIDGET_HIGHLIGHTEDPOST_LINE:
                return '';
        }

        return parent::getBodyClass($componentVariation, $props);
    }
    public function getItemWrapper(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_WIDGET_AUTHOR_CONTACT:
                return 'list-group-item';

            case self::MODULE_WIDGET_REFERENCES_LINE:
            case self::MODULE_WIDGET_HIGHLIGHTEDPOST_LINE:
                return '';
        }

        return parent::getItemWrapper($componentVariation, $props);
    }
    public function getWidgetClass(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_WIDGETCOMPACT_POST_AUTHORS:
            case self::MODULE_WIDGETCOMPACT_AUTHORDESCRIPTION:
                return 'panel panel-default panel-sm';

            case self::MODULE_WIDGET_REFERENCES_LINE:
            case self::MODULE_WIDGET_HIGHLIGHTEDPOST_LINE:
                // return 'panel panel-info panel-sm';
                return '';
        }

        return parent::getWidgetClass($componentVariation, $props);
    }
    public function getTitleWrapperClass(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_WIDGET_REFERENCES_LINE:
            case self::MODULE_WIDGET_HIGHLIGHTEDPOST_LINE:
                return '';
        }

        return parent::getTitleWrapperClass($componentVariation, $props);
    }
    public function getTitleClass(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_WIDGET_REFERENCES_LINE:
            case self::MODULE_WIDGET_HIGHLIGHTEDPOST_LINE:
                return '';
        }

        return parent::getTitleClass($componentVariation, $props);
    }
}


