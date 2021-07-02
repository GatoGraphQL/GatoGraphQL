<?php

class Wassup_Module_Processor_WidgetWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public const MODULE_LAYOUTWRAPPER_CATEGORIES = 'layoutwrapper-categories';
    public const MODULE_LAYOUTWRAPPER_APPLIESTO = 'layoutwrapper-appliesto';
    public const MODULE_WIDGETWRAPPER_HIGHLIGHTS_SIMPLEVIEW = 'widgetwrapper-highlights-simpleview';
    public const MODULE_WIDGETWRAPPER_HIGHLIGHTS_FULLVIEW = 'widgetwrapper-highlights-fullview';
    public const MODULE_WIDGETWRAPPER_HIGHLIGHTS_DETAILS = 'widgetwrapper-highlights-details';
    public const MODULE_WIDGETWRAPPER_REFERENCEDBY_SIMPLEVIEW = 'widgetwrapper-referencedby-simpleview';
    public const MODULE_WIDGETWRAPPER_REFERENCEDBY_FULLVIEW = 'widgetwrapper-referencedby-fullview';
    public const MODULE_WIDGETWRAPPER_REFERENCEDBY_DETAILS = 'widgetwrapper-referencedby-details';
    public const MODULE_WIDGETWRAPPER_HIGHLIGHTS_APPENDTOSCRIPT_SIMPLEVIEW = 'widgetwrapper-highrefby-simpleview';
    public const MODULE_WIDGETWRAPPER_HIGHLIGHTS_APPENDTOSCRIPT_FULLVIEW = 'widgetwrapper-highrefby-fullview';
    public const MODULE_WIDGETWRAPPER_HIGHLIGHTS_APPENDTOSCRIPT_DETAILS = 'widgetwrapper-highrefby-details';
    public const MODULE_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_DETAILS = 'widgetwrapper-refby-details';
    public const MODULE_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW = 'widgetwrapper-refby-simpleview';
    public const MODULE_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW = 'widgetwrapper-refby-fullview';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUTWRAPPER_CATEGORIES],
            [self::class, self::MODULE_LAYOUTWRAPPER_APPLIESTO],
            [self::class, self::MODULE_WIDGETWRAPPER_HIGHLIGHTS_SIMPLEVIEW],
            [self::class, self::MODULE_WIDGETWRAPPER_HIGHLIGHTS_FULLVIEW],
            [self::class, self::MODULE_WIDGETWRAPPER_HIGHLIGHTS_DETAILS],
            [self::class, self::MODULE_WIDGETWRAPPER_HIGHLIGHTS_APPENDTOSCRIPT_SIMPLEVIEW],
            [self::class, self::MODULE_WIDGETWRAPPER_HIGHLIGHTS_APPENDTOSCRIPT_FULLVIEW],
            [self::class, self::MODULE_WIDGETWRAPPER_HIGHLIGHTS_APPENDTOSCRIPT_DETAILS],
            [self::class, self::MODULE_WIDGETWRAPPER_REFERENCEDBY_SIMPLEVIEW],
            [self::class, self::MODULE_WIDGETWRAPPER_REFERENCEDBY_FULLVIEW],
            [self::class, self::MODULE_WIDGETWRAPPER_REFERENCEDBY_DETAILS],
            [self::class, self::MODULE_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_DETAILS],
            [self::class, self::MODULE_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW],
            [self::class, self::MODULE_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW],
        );
    }

    public function getConditionSucceededSubmodules(array $module)
    {
        $ret = parent::getConditionSucceededSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_LAYOUTWRAPPER_CATEGORIES:
                $ret[] = [Wassup_Module_Processor_CategoriesLayouts::class, Wassup_Module_Processor_CategoriesLayouts::MODULE_LAYOUT_CATEGORIES];
                break;

            case self::MODULE_LAYOUTWRAPPER_APPLIESTO:
                $ret[] = [Wassup_Module_Processor_CategoriesLayouts::class, Wassup_Module_Processor_CategoriesLayouts::MODULE_LAYOUT_APPLIESTO];
                break;

            case self::MODULE_WIDGETWRAPPER_HIGHLIGHTS_SIMPLEVIEW:
                $ret[] = [Wassup_Module_Processor_Widgets::class, Wassup_Module_Processor_Widgets::MODULE_WIDGET_HIGHLIGHTS_SIMPLEVIEW];
                break;

            case self::MODULE_WIDGETWRAPPER_HIGHLIGHTS_FULLVIEW:
                $ret[] = [Wassup_Module_Processor_Widgets::class, Wassup_Module_Processor_Widgets::MODULE_WIDGET_HIGHLIGHTS_FULLVIEW];
                break;

            case self::MODULE_WIDGETWRAPPER_HIGHLIGHTS_DETAILS:
                $ret[] = [Wassup_Module_Processor_Widgets::class, Wassup_Module_Processor_Widgets::MODULE_WIDGET_HIGHLIGHTS_DETAILS];
                break;

            case self::MODULE_WIDGETWRAPPER_HIGHLIGHTS_APPENDTOSCRIPT_SIMPLEVIEW:
                $ret[] = [Wassup_Module_Processor_Widgets::class, Wassup_Module_Processor_Widgets::MODULE_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_SIMPLEVIEW];
                break;

            case self::MODULE_WIDGETWRAPPER_HIGHLIGHTS_APPENDTOSCRIPT_FULLVIEW:
                $ret[] = [Wassup_Module_Processor_Widgets::class, Wassup_Module_Processor_Widgets::MODULE_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_FULLVIEW];
                break;

            case self::MODULE_WIDGETWRAPPER_HIGHLIGHTS_APPENDTOSCRIPT_DETAILS:
                $ret[] = [Wassup_Module_Processor_Widgets::class, Wassup_Module_Processor_Widgets::MODULE_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_DETAILS];
                break;

            case self::MODULE_WIDGETWRAPPER_REFERENCEDBY_SIMPLEVIEW:
                $ret[] = [Wassup_Module_Processor_Widgets::class, Wassup_Module_Processor_Widgets::MODULE_WIDGET_REFERENCEDBY_SIMPLEVIEW];
                break;

            case self::MODULE_WIDGETWRAPPER_REFERENCEDBY_FULLVIEW:
                $ret[] = [Wassup_Module_Processor_Widgets::class, Wassup_Module_Processor_Widgets::MODULE_WIDGET_REFERENCEDBY_FULLVIEW];
                break;

            case self::MODULE_WIDGETWRAPPER_REFERENCEDBY_DETAILS:
                $ret[] = [Wassup_Module_Processor_Widgets::class, Wassup_Module_Processor_Widgets::MODULE_WIDGET_REFERENCEDBY_DETAILS];
                break;

            case self::MODULE_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_DETAILS:
                $ret[] = [Wassup_Module_Processor_Widgets::class, Wassup_Module_Processor_Widgets::MODULE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_DETAILS];
                break;

            case self::MODULE_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW:
                $ret[] = [Wassup_Module_Processor_Widgets::class, Wassup_Module_Processor_Widgets::MODULE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW];
                break;

            case self::MODULE_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:
                $ret[] = [Wassup_Module_Processor_Widgets::class, Wassup_Module_Processor_Widgets::MODULE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW];
                break;
        }

        return $ret;
    }

    public function getConditionFailedSubmodules(array $module)
    {
        $ret = parent::getConditionFailedSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_WIDGETWRAPPER_HIGHLIGHTS_APPENDTOSCRIPT_SIMPLEVIEW:
            case self::MODULE_WIDGETWRAPPER_HIGHLIGHTS_APPENDTOSCRIPT_FULLVIEW:
            case self::MODULE_WIDGETWRAPPER_HIGHLIGHTS_APPENDTOSCRIPT_DETAILS:
                $ret[] = [PoP_Module_Processor_HighlightReferencesFramesLayouts::class, PoP_Module_Processor_HighlightReferencesFramesLayouts::MODULE_LAYOUT_HIGHLIGHTSEMPTY_APPENDTOSCRIPT];
                break;

            case self::MODULE_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_DETAILS:
                $ret[] = [PoP_Module_Processor_ReferencesFramesLayouts::class, PoP_Module_Processor_ReferencesFramesLayouts::MODULE_LAYOUT_REFERENCEDBYEMPTY_APPENDTOSCRIPT_DETAILS];
                break;

            case self::MODULE_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW:
                $ret[] = [PoP_Module_Processor_ReferencesFramesLayouts::class, PoP_Module_Processor_ReferencesFramesLayouts::MODULE_LAYOUT_REFERENCEDBYEMPTY_APPENDTOSCRIPT_SIMPLEVIEW];
                break;

            case self::MODULE_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:
                $ret[] = [PoP_Module_Processor_ReferencesFramesLayouts::class, PoP_Module_Processor_ReferencesFramesLayouts::MODULE_LAYOUT_REFERENCEDBYEMPTY_APPENDTOSCRIPT_FULLVIEW];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_WIDGETWRAPPER_HIGHLIGHTS_SIMPLEVIEW:
            case self::MODULE_WIDGETWRAPPER_HIGHLIGHTS_FULLVIEW:
            case self::MODULE_WIDGETWRAPPER_HIGHLIGHTS_DETAILS:
            case self::MODULE_WIDGETWRAPPER_HIGHLIGHTS_APPENDTOSCRIPT_SIMPLEVIEW:
            case self::MODULE_WIDGETWRAPPER_HIGHLIGHTS_APPENDTOSCRIPT_FULLVIEW:
            case self::MODULE_WIDGETWRAPPER_HIGHLIGHTS_APPENDTOSCRIPT_DETAILS:
            case self::MODULE_WIDGETWRAPPER_REFERENCEDBY_DETAILS:
            case self::MODULE_WIDGETWRAPPER_REFERENCEDBY_SIMPLEVIEW:
            case self::MODULE_WIDGETWRAPPER_REFERENCEDBY_FULLVIEW:
            case self::MODULE_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_DETAILS:
            case self::MODULE_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW:
            case self::MODULE_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:
                $this->appendProp($module, $props, 'class', 'referencedby clearfix');
                break;
        }

        parent::initModelProps($module, $props);
    }

    public function getConditionField(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUTWRAPPER_CATEGORIES:
                return 'hasTopics';

            case self::MODULE_LAYOUTWRAPPER_APPLIESTO:
                return 'hasAppliesto';

            case self::MODULE_WIDGETWRAPPER_HIGHLIGHTS_SIMPLEVIEW:
            case self::MODULE_WIDGETWRAPPER_HIGHLIGHTS_FULLVIEW:
            case self::MODULE_WIDGETWRAPPER_HIGHLIGHTS_DETAILS:
            case self::MODULE_WIDGETWRAPPER_HIGHLIGHTS_APPENDTOSCRIPT_SIMPLEVIEW:
            case self::MODULE_WIDGETWRAPPER_HIGHLIGHTS_APPENDTOSCRIPT_FULLVIEW:
            case self::MODULE_WIDGETWRAPPER_HIGHLIGHTS_APPENDTOSCRIPT_DETAILS:
                return 'hasHighlights';

            case self::MODULE_WIDGETWRAPPER_REFERENCEDBY_DETAILS:
            case self::MODULE_WIDGETWRAPPER_REFERENCEDBY_SIMPLEVIEW:
            case self::MODULE_WIDGETWRAPPER_REFERENCEDBY_FULLVIEW:
            case self::MODULE_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_DETAILS:
            case self::MODULE_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW:
            case self::MODULE_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:
                return 'hasReferencedBy';
        }

        return null;
    }
}



