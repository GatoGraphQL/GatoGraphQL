<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class Wassup_Module_Processor_Widgets extends PoP_Module_Processor_WidgetsBase
{
    public final const COMPONENT_WIDGET_HIGHLIGHTS = 'widget-highlights';
    public final const COMPONENT_WIDGET_HIGHLIGHTS_SIMPLEVIEW = 'widget-highlights-simpleview';
    public final const COMPONENT_WIDGET_HIGHLIGHTS_FULLVIEW = 'widget-highlights-fullview';
    public final const COMPONENT_WIDGET_HIGHLIGHTS_DETAILS = 'widget-highlights-details';
    public final const COMPONENT_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_SIMPLEVIEW = 'widget-highlights-appendtoscript-simpleview';
    public final const COMPONENT_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_FULLVIEW = 'widget-highlights-appendtoscript-fullview';
    public final const COMPONENT_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_DETAILS = 'widget-highlights-appendtoscript-details';
    public final const COMPONENT_WIDGET_REFERENCEDBY_SIMPLEVIEW = 'widget-referencedby-simpleview';
    public final const COMPONENT_WIDGET_REFERENCEDBY_FULLVIEW = 'widget-referencedby-fullview';
    public final const COMPONENT_WIDGET_REFERENCEDBY_DETAILS = 'widget-referencedby-details';
    public final const COMPONENT_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_DETAILS = 'widget-referencedby-appendtoscript-details';
    public final const COMPONENT_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW = 'widget-referencedby-appendtoscript-simpleview';
    public final const COMPONENT_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW = 'widget-referencedby-appendtoscript-fullview';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_WIDGET_HIGHLIGHTS,
            self::COMPONENT_WIDGET_HIGHLIGHTS_SIMPLEVIEW,
            self::COMPONENT_WIDGET_HIGHLIGHTS_FULLVIEW,
            self::COMPONENT_WIDGET_HIGHLIGHTS_DETAILS,
            self::COMPONENT_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_SIMPLEVIEW,
            self::COMPONENT_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_FULLVIEW,
            self::COMPONENT_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_DETAILS,
            self::COMPONENT_WIDGET_REFERENCEDBY_SIMPLEVIEW,
            self::COMPONENT_WIDGET_REFERENCEDBY_FULLVIEW,
            self::COMPONENT_WIDGET_REFERENCEDBY_DETAILS,
            self::COMPONENT_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_DETAILS,
            self::COMPONENT_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW,
            self::COMPONENT_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_WIDGET_HIGHLIGHTS:
                $ret[] = [PoP_Module_Processor_HighlightedPostSubcomponentLayouts::class, PoP_Module_Processor_HighlightedPostSubcomponentLayouts::COMPONENT_LAYOUT_HIGHLIGHTEDPOST_ADDONS];
                break;

            case self::COMPONENT_WIDGET_HIGHLIGHTS_SIMPLEVIEW:
            case self::COMPONENT_WIDGET_HIGHLIGHTS_FULLVIEW:
            case self::COMPONENT_WIDGET_HIGHLIGHTS_DETAILS:
                $ret[] = [PoP_Module_Processor_HighlightReferencedbyLayouts::class, PoP_Module_Processor_HighlightReferencedbyLayouts::COMPONENT_SUBCOMPONENT_HIGHLIGHTS];
                break;

            case self::COMPONENT_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_SIMPLEVIEW:
            case self::COMPONENT_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_FULLVIEW:
            case self::COMPONENT_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_DETAILS:
                $ret[] = [PoP_Module_Processor_HighlightReferencesFramesLayouts::class, PoP_Module_Processor_HighlightReferencesFramesLayouts::COMPONENT_LAYOUT_HIGHLIGHTS_APPENDTOSCRIPT];
                break;

            case self::COMPONENT_WIDGET_REFERENCEDBY_SIMPLEVIEW:
                $ret[] = [PoP_Module_Processor_ReferencedbyLayouts::class, PoP_Module_Processor_ReferencedbyLayouts::COMPONENT_SUBCOMPONENT_REFERENCEDBY_SIMPLEVIEW];
                break;

            case self::COMPONENT_WIDGET_REFERENCEDBY_FULLVIEW:
                $ret[] = [PoP_Module_Processor_ReferencedbyLayouts::class, PoP_Module_Processor_ReferencedbyLayouts::COMPONENT_SUBCOMPONENT_REFERENCEDBY_FULLVIEW];
                break;

            case self::COMPONENT_WIDGET_REFERENCEDBY_DETAILS:
                $ret[] = [PoP_Module_Processor_ReferencedbyLayouts::class, PoP_Module_Processor_ReferencedbyLayouts::COMPONENT_SUBCOMPONENT_REFERENCEDBY_DETAILS];
                break;

            case self::COMPONENT_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_DETAILS:
                $ret[] = [PoP_Module_Processor_ReferencesFramesLayouts::class, PoP_Module_Processor_ReferencesFramesLayouts::COMPONENT_LAYOUT_REFERENCEDBY_APPENDTOSCRIPT_DETAILS];
                break;

            case self::COMPONENT_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW:
                $ret[] = [PoP_Module_Processor_ReferencesFramesLayouts::class, PoP_Module_Processor_ReferencesFramesLayouts::COMPONENT_LAYOUT_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW];
                break;

            case self::COMPONENT_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:
                $ret[] = [PoP_Module_Processor_ReferencesFramesLayouts::class, PoP_Module_Processor_ReferencesFramesLayouts::COMPONENT_LAYOUT_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW];
                break;
        }

        return $ret;
    }

    public function getMenuTitle(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $extracts = TranslationAPIFacade::getInstance()->__('Highlights', 'poptheme-wassup');
        $additionals = TranslationAPIFacade::getInstance()->__('Responses', 'pop-coreprocessors');
        $titles = array(
            self::COMPONENT_WIDGET_HIGHLIGHTS => TranslationAPIFacade::getInstance()->__('Highlighted from', 'poptheme-wassup'),
            self::COMPONENT_WIDGET_HIGHLIGHTS_SIMPLEVIEW => $extracts,
            self::COMPONENT_WIDGET_HIGHLIGHTS_FULLVIEW => $extracts,
            self::COMPONENT_WIDGET_HIGHLIGHTS_DETAILS => $extracts,
            self::COMPONENT_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_SIMPLEVIEW => $extracts,
            self::COMPONENT_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_FULLVIEW => $extracts,
            self::COMPONENT_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_DETAILS => $extracts,
            self::COMPONENT_WIDGET_REFERENCEDBY_SIMPLEVIEW => $additionals,
            self::COMPONENT_WIDGET_REFERENCEDBY_FULLVIEW => $additionals,
            self::COMPONENT_WIDGET_REFERENCEDBY_DETAILS => $additionals,
            self::COMPONENT_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_DETAILS => $additionals,
            self::COMPONENT_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW => $additionals,
            self::COMPONENT_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW => $additionals,
        );

        return $titles[$component->name] ?? null;
    }
    public function getFontawesome(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $fontawesomes = array(
            self::COMPONENT_WIDGET_HIGHLIGHTS => 'fa-asterisk',
            self::COMPONENT_WIDGET_HIGHLIGHTS_SIMPLEVIEW => 'fa-bullseye',
            self::COMPONENT_WIDGET_HIGHLIGHTS_FULLVIEW => 'fa-bullseye',
            self::COMPONENT_WIDGET_HIGHLIGHTS_DETAILS => 'fa-bullseye',
            self::COMPONENT_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_SIMPLEVIEW => 'fa-bullseye',
            self::COMPONENT_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_FULLVIEW => 'fa-bullseye',
            self::COMPONENT_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_DETAILS => 'fa-bullseye',
            self::COMPONENT_WIDGET_REFERENCEDBY_SIMPLEVIEW => 'fa-asterisk',
            self::COMPONENT_WIDGET_REFERENCEDBY_FULLVIEW => 'fa-asterisk',
            self::COMPONENT_WIDGET_REFERENCEDBY_DETAILS => 'fa-asterisk',
            self::COMPONENT_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_DETAILS => 'fa-asterisk',
            self::COMPONENT_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW => 'fa-asterisk',
            self::COMPONENT_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW => 'fa-asterisk',
        );

        return $fontawesomes[$component->name] ?? null;
    }
    public function getBodyClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_WIDGET_HIGHLIGHTS_SIMPLEVIEW:
            case self::COMPONENT_WIDGET_HIGHLIGHTS_FULLVIEW:
            case self::COMPONENT_WIDGET_HIGHLIGHTS_DETAILS:
            case self::COMPONENT_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_SIMPLEVIEW:
            case self::COMPONENT_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_FULLVIEW:
            case self::COMPONENT_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_DETAILS:
            case self::COMPONENT_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW:
                return '';

            case self::COMPONENT_WIDGET_REFERENCEDBY_DETAILS:
            case self::COMPONENT_WIDGET_REFERENCEDBY_SIMPLEVIEW:
            case self::COMPONENT_WIDGET_REFERENCEDBY_FULLVIEW:
            case self::COMPONENT_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_DETAILS:
            case self::COMPONENT_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:
                return 'panel-body';
        }

        return parent::getBodyClass($component, $props);
    }
    public function getItemWrapper(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_WIDGET_HIGHLIGHTS_SIMPLEVIEW:
            case self::COMPONENT_WIDGET_HIGHLIGHTS_FULLVIEW:
            case self::COMPONENT_WIDGET_HIGHLIGHTS_DETAILS:
            case self::COMPONENT_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_SIMPLEVIEW:
            case self::COMPONENT_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_FULLVIEW:
            case self::COMPONENT_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_DETAILS:
            case self::COMPONENT_WIDGET_REFERENCEDBY_DETAILS:
            case self::COMPONENT_WIDGET_REFERENCEDBY_SIMPLEVIEW:
            case self::COMPONENT_WIDGET_REFERENCEDBY_FULLVIEW:
            case self::COMPONENT_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_DETAILS:
            case self::COMPONENT_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW:
            case self::COMPONENT_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:
                return '';
        }

        return parent::getItemWrapper($component, $props);
    }
    public function getWidgetClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_WIDGET_HIGHLIGHTS_SIMPLEVIEW:
            case self::COMPONENT_WIDGET_HIGHLIGHTS_FULLVIEW:
            case self::COMPONENT_WIDGET_HIGHLIGHTS_DETAILS:
            case self::COMPONENT_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_SIMPLEVIEW:
            case self::COMPONENT_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_FULLVIEW:
            case self::COMPONENT_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_DETAILS:
            case self::COMPONENT_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW:
                return '';

            case self::COMPONENT_WIDGET_REFERENCEDBY_DETAILS:
            case self::COMPONENT_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_DETAILS:
            case self::COMPONENT_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:
                return 'panel panel-default panel-sm';
        }

        return parent::getWidgetClass($component, $props);
    }
    public function getTitleWrapperClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_WIDGET_HIGHLIGHTS_SIMPLEVIEW:
            case self::COMPONENT_WIDGET_HIGHLIGHTS_FULLVIEW:
            case self::COMPONENT_WIDGET_HIGHLIGHTS_DETAILS:
            case self::COMPONENT_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_SIMPLEVIEW:
            case self::COMPONENT_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_FULLVIEW:
            case self::COMPONENT_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_DETAILS:
            case self::COMPONENT_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW:
                return '';
        }

        return parent::getTitleWrapperClass($component, $props);
    }
    public function getTitleClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_WIDGET_HIGHLIGHTS_SIMPLEVIEW:
            case self::COMPONENT_WIDGET_HIGHLIGHTS_FULLVIEW:
            case self::COMPONENT_WIDGET_HIGHLIGHTS_DETAILS:
                return '';

            case self::COMPONENT_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW:
                return 'panel-title';
        }

        return parent::getTitleClass($component, $props);
    }
    public function getQuicklinkgroupSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_WIDGET_HIGHLIGHTS_SIMPLEVIEW:
            case self::COMPONENT_WIDGET_HIGHLIGHTS_FULLVIEW:
            case self::COMPONENT_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_SIMPLEVIEW:
            case self::COMPONENT_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_FULLVIEW:
                return [PoP_AddHighlights_Module_Processor_PostButtons::class, PoP_AddHighlights_Module_Processor_PostButtons::COMPONENT_BUTTON_HIGHLIGHT_CREATEBTN];

            case self::COMPONENT_WIDGET_REFERENCEDBY_SIMPLEVIEW:
            case self::COMPONENT_WIDGET_REFERENCEDBY_FULLVIEW:
            case self::COMPONENT_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW:
            case self::COMPONENT_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:
                return [PoP_AddRelatedPosts_Module_Processor_ControlButtonGroups::class, PoP_AddRelatedPosts_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_ADDRELATEDPOST];
        }

        return parent::getQuicklinkgroupSubcomponent($component);
    }
    public function collapsible(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_WIDGET_HIGHLIGHTS_DETAILS:
            case self::COMPONENT_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_DETAILS:
            case self::COMPONENT_WIDGET_REFERENCEDBY_DETAILS:
            case self::COMPONENT_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_DETAILS:
                return true;
        }

        return parent::collapsible($component, $props);
    }
}


