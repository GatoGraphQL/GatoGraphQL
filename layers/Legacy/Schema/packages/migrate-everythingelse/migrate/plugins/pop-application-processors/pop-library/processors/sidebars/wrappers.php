<?php

class Wassup_Module_Processor_WidgetWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public final const MODULE_LAYOUTWRAPPER_CATEGORIES = 'layoutwrapper-categories';
    public final const MODULE_LAYOUTWRAPPER_APPLIESTO = 'layoutwrapper-appliesto';
    public final const MODULE_WIDGETWRAPPER_HIGHLIGHTS_SIMPLEVIEW = 'widgetwrapper-highlights-simpleview';
    public final const MODULE_WIDGETWRAPPER_HIGHLIGHTS_FULLVIEW = 'widgetwrapper-highlights-fullview';
    public final const MODULE_WIDGETWRAPPER_HIGHLIGHTS_DETAILS = 'widgetwrapper-highlights-details';
    public final const MODULE_WIDGETWRAPPER_REFERENCEDBY_SIMPLEVIEW = 'widgetwrapper-referencedby-simpleview';
    public final const MODULE_WIDGETWRAPPER_REFERENCEDBY_FULLVIEW = 'widgetwrapper-referencedby-fullview';
    public final const MODULE_WIDGETWRAPPER_REFERENCEDBY_DETAILS = 'widgetwrapper-referencedby-details';
    public final const MODULE_WIDGETWRAPPER_HIGHLIGHTS_APPENDTOSCRIPT_SIMPLEVIEW = 'widgetwrapper-highrefby-simpleview';
    public final const MODULE_WIDGETWRAPPER_HIGHLIGHTS_APPENDTOSCRIPT_FULLVIEW = 'widgetwrapper-highrefby-fullview';
    public final const MODULE_WIDGETWRAPPER_HIGHLIGHTS_APPENDTOSCRIPT_DETAILS = 'widgetwrapper-highrefby-details';
    public final const MODULE_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_DETAILS = 'widgetwrapper-refby-details';
    public final const MODULE_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW = 'widgetwrapper-refby-simpleview';
    public final const MODULE_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW = 'widgetwrapper-refby-fullview';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUTWRAPPER_CATEGORIES],
            [self::class, self::COMPONENT_LAYOUTWRAPPER_APPLIESTO],
            [self::class, self::COMPONENT_WIDGETWRAPPER_HIGHLIGHTS_SIMPLEVIEW],
            [self::class, self::COMPONENT_WIDGETWRAPPER_HIGHLIGHTS_FULLVIEW],
            [self::class, self::COMPONENT_WIDGETWRAPPER_HIGHLIGHTS_DETAILS],
            [self::class, self::COMPONENT_WIDGETWRAPPER_HIGHLIGHTS_APPENDTOSCRIPT_SIMPLEVIEW],
            [self::class, self::COMPONENT_WIDGETWRAPPER_HIGHLIGHTS_APPENDTOSCRIPT_FULLVIEW],
            [self::class, self::COMPONENT_WIDGETWRAPPER_HIGHLIGHTS_APPENDTOSCRIPT_DETAILS],
            [self::class, self::COMPONENT_WIDGETWRAPPER_REFERENCEDBY_SIMPLEVIEW],
            [self::class, self::COMPONENT_WIDGETWRAPPER_REFERENCEDBY_FULLVIEW],
            [self::class, self::COMPONENT_WIDGETWRAPPER_REFERENCEDBY_DETAILS],
            [self::class, self::COMPONENT_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_DETAILS],
            [self::class, self::COMPONENT_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW],
            [self::class, self::COMPONENT_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW],
        );
    }

    public function getConditionSucceededSubmodules(array $component)
    {
        $ret = parent::getConditionSucceededSubmodules($component);

        switch ($component[1]) {
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

    public function getConditionFailedSubmodules(array $component)
    {
        $ret = parent::getConditionFailedSubmodules($component);

        switch ($component[1]) {
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

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
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

    public function getConditionField(array $component): ?string
    {
        switch ($component[1]) {
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



