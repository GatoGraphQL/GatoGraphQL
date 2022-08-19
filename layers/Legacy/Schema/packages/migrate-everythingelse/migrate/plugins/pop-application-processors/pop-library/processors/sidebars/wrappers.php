<?php

class Wassup_Module_Processor_WidgetWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public final const COMPONENT_LAYOUTWRAPPER_CATEGORIES = 'layoutwrapper-categories';
    public final const COMPONENT_LAYOUTWRAPPER_APPLIESTO = 'layoutwrapper-appliesto';
    public final const COMPONENT_WIDGETWRAPPER_HIGHLIGHTS_SIMPLEVIEW = 'widgetwrapper-highlights-simpleview';
    public final const COMPONENT_WIDGETWRAPPER_HIGHLIGHTS_FULLVIEW = 'widgetwrapper-highlights-fullview';
    public final const COMPONENT_WIDGETWRAPPER_HIGHLIGHTS_DETAILS = 'widgetwrapper-highlights-details';
    public final const COMPONENT_WIDGETWRAPPER_REFERENCEDBY_SIMPLEVIEW = 'widgetwrapper-referencedby-simpleview';
    public final const COMPONENT_WIDGETWRAPPER_REFERENCEDBY_FULLVIEW = 'widgetwrapper-referencedby-fullview';
    public final const COMPONENT_WIDGETWRAPPER_REFERENCEDBY_DETAILS = 'widgetwrapper-referencedby-details';
    public final const COMPONENT_WIDGETWRAPPER_HIGHLIGHTS_APPENDTOSCRIPT_SIMPLEVIEW = 'widgetwrapper-highrefby-simpleview';
    public final const COMPONENT_WIDGETWRAPPER_HIGHLIGHTS_APPENDTOSCRIPT_FULLVIEW = 'widgetwrapper-highrefby-fullview';
    public final const COMPONENT_WIDGETWRAPPER_HIGHLIGHTS_APPENDTOSCRIPT_DETAILS = 'widgetwrapper-highrefby-details';
    public final const COMPONENT_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_DETAILS = 'widgetwrapper-refby-details';
    public final const COMPONENT_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW = 'widgetwrapper-refby-simpleview';
    public final const COMPONENT_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW = 'widgetwrapper-refby-fullview';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUTWRAPPER_CATEGORIES,
            self::COMPONENT_LAYOUTWRAPPER_APPLIESTO,
            self::COMPONENT_WIDGETWRAPPER_HIGHLIGHTS_SIMPLEVIEW,
            self::COMPONENT_WIDGETWRAPPER_HIGHLIGHTS_FULLVIEW,
            self::COMPONENT_WIDGETWRAPPER_HIGHLIGHTS_DETAILS,
            self::COMPONENT_WIDGETWRAPPER_HIGHLIGHTS_APPENDTOSCRIPT_SIMPLEVIEW,
            self::COMPONENT_WIDGETWRAPPER_HIGHLIGHTS_APPENDTOSCRIPT_FULLVIEW,
            self::COMPONENT_WIDGETWRAPPER_HIGHLIGHTS_APPENDTOSCRIPT_DETAILS,
            self::COMPONENT_WIDGETWRAPPER_REFERENCEDBY_SIMPLEVIEW,
            self::COMPONENT_WIDGETWRAPPER_REFERENCEDBY_FULLVIEW,
            self::COMPONENT_WIDGETWRAPPER_REFERENCEDBY_DETAILS,
            self::COMPONENT_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_DETAILS,
            self::COMPONENT_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW,
            self::COMPONENT_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW,
        );
    }

    public function getConditionSucceededSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getConditionSucceededSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_LAYOUTWRAPPER_CATEGORIES:
                $ret[] = [Wassup_Module_Processor_CategoriesLayouts::class, Wassup_Module_Processor_CategoriesLayouts::COMPONENT_LAYOUT_CATEGORIES];
                break;

            case self::COMPONENT_LAYOUTWRAPPER_APPLIESTO:
                $ret[] = [Wassup_Module_Processor_CategoriesLayouts::class, Wassup_Module_Processor_CategoriesLayouts::COMPONENT_LAYOUT_APPLIESTO];
                break;

            case self::COMPONENT_WIDGETWRAPPER_HIGHLIGHTS_SIMPLEVIEW:
                $ret[] = [Wassup_Module_Processor_Widgets::class, Wassup_Module_Processor_Widgets::COMPONENT_WIDGET_HIGHLIGHTS_SIMPLEVIEW];
                break;

            case self::COMPONENT_WIDGETWRAPPER_HIGHLIGHTS_FULLVIEW:
                $ret[] = [Wassup_Module_Processor_Widgets::class, Wassup_Module_Processor_Widgets::COMPONENT_WIDGET_HIGHLIGHTS_FULLVIEW];
                break;

            case self::COMPONENT_WIDGETWRAPPER_HIGHLIGHTS_DETAILS:
                $ret[] = [Wassup_Module_Processor_Widgets::class, Wassup_Module_Processor_Widgets::COMPONENT_WIDGET_HIGHLIGHTS_DETAILS];
                break;

            case self::COMPONENT_WIDGETWRAPPER_HIGHLIGHTS_APPENDTOSCRIPT_SIMPLEVIEW:
                $ret[] = [Wassup_Module_Processor_Widgets::class, Wassup_Module_Processor_Widgets::COMPONENT_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_SIMPLEVIEW];
                break;

            case self::COMPONENT_WIDGETWRAPPER_HIGHLIGHTS_APPENDTOSCRIPT_FULLVIEW:
                $ret[] = [Wassup_Module_Processor_Widgets::class, Wassup_Module_Processor_Widgets::COMPONENT_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_FULLVIEW];
                break;

            case self::COMPONENT_WIDGETWRAPPER_HIGHLIGHTS_APPENDTOSCRIPT_DETAILS:
                $ret[] = [Wassup_Module_Processor_Widgets::class, Wassup_Module_Processor_Widgets::COMPONENT_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_DETAILS];
                break;

            case self::COMPONENT_WIDGETWRAPPER_REFERENCEDBY_SIMPLEVIEW:
                $ret[] = [Wassup_Module_Processor_Widgets::class, Wassup_Module_Processor_Widgets::COMPONENT_WIDGET_REFERENCEDBY_SIMPLEVIEW];
                break;

            case self::COMPONENT_WIDGETWRAPPER_REFERENCEDBY_FULLVIEW:
                $ret[] = [Wassup_Module_Processor_Widgets::class, Wassup_Module_Processor_Widgets::COMPONENT_WIDGET_REFERENCEDBY_FULLVIEW];
                break;

            case self::COMPONENT_WIDGETWRAPPER_REFERENCEDBY_DETAILS:
                $ret[] = [Wassup_Module_Processor_Widgets::class, Wassup_Module_Processor_Widgets::COMPONENT_WIDGET_REFERENCEDBY_DETAILS];
                break;

            case self::COMPONENT_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_DETAILS:
                $ret[] = [Wassup_Module_Processor_Widgets::class, Wassup_Module_Processor_Widgets::COMPONENT_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_DETAILS];
                break;

            case self::COMPONENT_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW:
                $ret[] = [Wassup_Module_Processor_Widgets::class, Wassup_Module_Processor_Widgets::COMPONENT_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW];
                break;

            case self::COMPONENT_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:
                $ret[] = [Wassup_Module_Processor_Widgets::class, Wassup_Module_Processor_Widgets::COMPONENT_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW];
                break;
        }

        return $ret;
    }

    public function getConditionFailedSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getConditionFailedSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_WIDGETWRAPPER_HIGHLIGHTS_APPENDTOSCRIPT_SIMPLEVIEW:
            case self::COMPONENT_WIDGETWRAPPER_HIGHLIGHTS_APPENDTOSCRIPT_FULLVIEW:
            case self::COMPONENT_WIDGETWRAPPER_HIGHLIGHTS_APPENDTOSCRIPT_DETAILS:
                $ret[] = [PoP_Module_Processor_HighlightReferencesFramesLayouts::class, PoP_Module_Processor_HighlightReferencesFramesLayouts::COMPONENT_LAYOUT_HIGHLIGHTSEMPTY_APPENDTOSCRIPT];
                break;

            case self::COMPONENT_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_DETAILS:
                $ret[] = [PoP_Module_Processor_ReferencesFramesLayouts::class, PoP_Module_Processor_ReferencesFramesLayouts::COMPONENT_LAYOUT_REFERENCEDBYEMPTY_APPENDTOSCRIPT_DETAILS];
                break;

            case self::COMPONENT_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW:
                $ret[] = [PoP_Module_Processor_ReferencesFramesLayouts::class, PoP_Module_Processor_ReferencesFramesLayouts::COMPONENT_LAYOUT_REFERENCEDBYEMPTY_APPENDTOSCRIPT_SIMPLEVIEW];
                break;

            case self::COMPONENT_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:
                $ret[] = [PoP_Module_Processor_ReferencesFramesLayouts::class, PoP_Module_Processor_ReferencesFramesLayouts::COMPONENT_LAYOUT_REFERENCEDBYEMPTY_APPENDTOSCRIPT_FULLVIEW];
                break;
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_WIDGETWRAPPER_HIGHLIGHTS_SIMPLEVIEW:
            case self::COMPONENT_WIDGETWRAPPER_HIGHLIGHTS_FULLVIEW:
            case self::COMPONENT_WIDGETWRAPPER_HIGHLIGHTS_DETAILS:
            case self::COMPONENT_WIDGETWRAPPER_HIGHLIGHTS_APPENDTOSCRIPT_SIMPLEVIEW:
            case self::COMPONENT_WIDGETWRAPPER_HIGHLIGHTS_APPENDTOSCRIPT_FULLVIEW:
            case self::COMPONENT_WIDGETWRAPPER_HIGHLIGHTS_APPENDTOSCRIPT_DETAILS:
            case self::COMPONENT_WIDGETWRAPPER_REFERENCEDBY_DETAILS:
            case self::COMPONENT_WIDGETWRAPPER_REFERENCEDBY_SIMPLEVIEW:
            case self::COMPONENT_WIDGETWRAPPER_REFERENCEDBY_FULLVIEW:
            case self::COMPONENT_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_DETAILS:
            case self::COMPONENT_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW:
            case self::COMPONENT_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:
                $this->appendProp($component, $props, 'class', 'referencedby clearfix');
                break;
        }

        parent::initModelProps($component, $props);
    }

    public function getConditionField(\PoP\ComponentModel\Component\Component $component): ?string
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUTWRAPPER_CATEGORIES:
                return 'hasTopics';

            case self::COMPONENT_LAYOUTWRAPPER_APPLIESTO:
                return 'hasAppliesto';

            case self::COMPONENT_WIDGETWRAPPER_HIGHLIGHTS_SIMPLEVIEW:
            case self::COMPONENT_WIDGETWRAPPER_HIGHLIGHTS_FULLVIEW:
            case self::COMPONENT_WIDGETWRAPPER_HIGHLIGHTS_DETAILS:
            case self::COMPONENT_WIDGETWRAPPER_HIGHLIGHTS_APPENDTOSCRIPT_SIMPLEVIEW:
            case self::COMPONENT_WIDGETWRAPPER_HIGHLIGHTS_APPENDTOSCRIPT_FULLVIEW:
            case self::COMPONENT_WIDGETWRAPPER_HIGHLIGHTS_APPENDTOSCRIPT_DETAILS:
                return 'hasHighlights';

            case self::COMPONENT_WIDGETWRAPPER_REFERENCEDBY_DETAILS:
            case self::COMPONENT_WIDGETWRAPPER_REFERENCEDBY_SIMPLEVIEW:
            case self::COMPONENT_WIDGETWRAPPER_REFERENCEDBY_FULLVIEW:
            case self::COMPONENT_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_DETAILS:
            case self::COMPONENT_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW:
            case self::COMPONENT_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:
                return 'hasReferencedBy';
        }

        return null;
    }
}



