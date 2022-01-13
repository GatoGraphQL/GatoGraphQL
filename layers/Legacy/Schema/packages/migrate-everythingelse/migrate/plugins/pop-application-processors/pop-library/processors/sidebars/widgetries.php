<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class Wassup_Module_Processor_Widgets extends PoP_Module_Processor_WidgetsBase
{
    public const MODULE_WIDGET_HIGHLIGHTS = 'widget-highlights';
    public const MODULE_WIDGET_HIGHLIGHTS_SIMPLEVIEW = 'widget-highlights-simpleview';
    public const MODULE_WIDGET_HIGHLIGHTS_FULLVIEW = 'widget-highlights-fullview';
    public const MODULE_WIDGET_HIGHLIGHTS_DETAILS = 'widget-highlights-details';
    public const MODULE_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_SIMPLEVIEW = 'widget-highlights-appendtoscript-simpleview';
    public const MODULE_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_FULLVIEW = 'widget-highlights-appendtoscript-fullview';
    public const MODULE_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_DETAILS = 'widget-highlights-appendtoscript-details';
    public const MODULE_WIDGET_REFERENCEDBY_SIMPLEVIEW = 'widget-referencedby-simpleview';
    public const MODULE_WIDGET_REFERENCEDBY_FULLVIEW = 'widget-referencedby-fullview';
    public const MODULE_WIDGET_REFERENCEDBY_DETAILS = 'widget-referencedby-details';
    public const MODULE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_DETAILS = 'widget-referencedby-appendtoscript-details';
    public const MODULE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW = 'widget-referencedby-appendtoscript-simpleview';
    public const MODULE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW = 'widget-referencedby-appendtoscript-fullview';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_WIDGET_HIGHLIGHTS],
            [self::class, self::MODULE_WIDGET_HIGHLIGHTS_SIMPLEVIEW],
            [self::class, self::MODULE_WIDGET_HIGHLIGHTS_FULLVIEW],
            [self::class, self::MODULE_WIDGET_HIGHLIGHTS_DETAILS],
            [self::class, self::MODULE_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_SIMPLEVIEW],
            [self::class, self::MODULE_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_FULLVIEW],
            [self::class, self::MODULE_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_DETAILS],
            [self::class, self::MODULE_WIDGET_REFERENCEDBY_SIMPLEVIEW],
            [self::class, self::MODULE_WIDGET_REFERENCEDBY_FULLVIEW],
            [self::class, self::MODULE_WIDGET_REFERENCEDBY_DETAILS],
            [self::class, self::MODULE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_DETAILS],
            [self::class, self::MODULE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW],
            [self::class, self::MODULE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_WIDGET_HIGHLIGHTS:
                $ret[] = [PoP_Module_Processor_HighlightedPostSubcomponentLayouts::class, PoP_Module_Processor_HighlightedPostSubcomponentLayouts::MODULE_LAYOUT_HIGHLIGHTEDPOST_ADDONS];
                break;

            case self::MODULE_WIDGET_HIGHLIGHTS_SIMPLEVIEW:
            case self::MODULE_WIDGET_HIGHLIGHTS_FULLVIEW:
            case self::MODULE_WIDGET_HIGHLIGHTS_DETAILS:
                $ret[] = [PoP_Module_Processor_HighlightReferencedbyLayouts::class, PoP_Module_Processor_HighlightReferencedbyLayouts::MODULE_SUBCOMPONENT_HIGHLIGHTS];
                break;

            case self::MODULE_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_SIMPLEVIEW:
            case self::MODULE_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_FULLVIEW:
            case self::MODULE_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_DETAILS:
                $ret[] = [PoP_Module_Processor_HighlightReferencesFramesLayouts::class, PoP_Module_Processor_HighlightReferencesFramesLayouts::MODULE_LAYOUT_HIGHLIGHTS_APPENDTOSCRIPT];
                break;

            case self::MODULE_WIDGET_REFERENCEDBY_SIMPLEVIEW:
                $ret[] = [PoP_Module_Processor_ReferencedbyLayouts::class, PoP_Module_Processor_ReferencedbyLayouts::MODULE_SUBCOMPONENT_REFERENCEDBY_SIMPLEVIEW];
                break;

            case self::MODULE_WIDGET_REFERENCEDBY_FULLVIEW:
                $ret[] = [PoP_Module_Processor_ReferencedbyLayouts::class, PoP_Module_Processor_ReferencedbyLayouts::MODULE_SUBCOMPONENT_REFERENCEDBY_FULLVIEW];
                break;

            case self::MODULE_WIDGET_REFERENCEDBY_DETAILS:
                $ret[] = [PoP_Module_Processor_ReferencedbyLayouts::class, PoP_Module_Processor_ReferencedbyLayouts::MODULE_SUBCOMPONENT_REFERENCEDBY_DETAILS];
                break;

            case self::MODULE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_DETAILS:
                $ret[] = [PoP_Module_Processor_ReferencesFramesLayouts::class, PoP_Module_Processor_ReferencesFramesLayouts::MODULE_LAYOUT_REFERENCEDBY_APPENDTOSCRIPT_DETAILS];
                break;

            case self::MODULE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW:
                $ret[] = [PoP_Module_Processor_ReferencesFramesLayouts::class, PoP_Module_Processor_ReferencesFramesLayouts::MODULE_LAYOUT_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW];
                break;

            case self::MODULE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:
                $ret[] = [PoP_Module_Processor_ReferencesFramesLayouts::class, PoP_Module_Processor_ReferencesFramesLayouts::MODULE_LAYOUT_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW];
                break;
        }

        return $ret;
    }

    public function getMenuTitle(array $module, array &$props)
    {
        $extracts = TranslationAPIFacade::getInstance()->__('Highlights', 'poptheme-wassup');
        $additionals = TranslationAPIFacade::getInstance()->__('Responses', 'pop-coreprocessors');
        $titles = array(
            self::MODULE_WIDGET_HIGHLIGHTS => TranslationAPIFacade::getInstance()->__('Highlighted from', 'poptheme-wassup'),
            self::MODULE_WIDGET_HIGHLIGHTS_SIMPLEVIEW => $extracts,
            self::MODULE_WIDGET_HIGHLIGHTS_FULLVIEW => $extracts,
            self::MODULE_WIDGET_HIGHLIGHTS_DETAILS => $extracts,
            self::MODULE_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_SIMPLEVIEW => $extracts,
            self::MODULE_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_FULLVIEW => $extracts,
            self::MODULE_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_DETAILS => $extracts,
            self::MODULE_WIDGET_REFERENCEDBY_SIMPLEVIEW => $additionals,
            self::MODULE_WIDGET_REFERENCEDBY_FULLVIEW => $additionals,
            self::MODULE_WIDGET_REFERENCEDBY_DETAILS => $additionals,
            self::MODULE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_DETAILS => $additionals,
            self::MODULE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW => $additionals,
            self::MODULE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW => $additionals,
        );

        return $titles[$module[1]] ?? null;
    }
    public function getFontawesome(array $module, array &$props)
    {
        $fontawesomes = array(
            self::MODULE_WIDGET_HIGHLIGHTS => 'fa-asterisk',
            self::MODULE_WIDGET_HIGHLIGHTS_SIMPLEVIEW => 'fa-bullseye',
            self::MODULE_WIDGET_HIGHLIGHTS_FULLVIEW => 'fa-bullseye',
            self::MODULE_WIDGET_HIGHLIGHTS_DETAILS => 'fa-bullseye',
            self::MODULE_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_SIMPLEVIEW => 'fa-bullseye',
            self::MODULE_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_FULLVIEW => 'fa-bullseye',
            self::MODULE_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_DETAILS => 'fa-bullseye',
            self::MODULE_WIDGET_REFERENCEDBY_SIMPLEVIEW => 'fa-asterisk',
            self::MODULE_WIDGET_REFERENCEDBY_FULLVIEW => 'fa-asterisk',
            self::MODULE_WIDGET_REFERENCEDBY_DETAILS => 'fa-asterisk',
            self::MODULE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_DETAILS => 'fa-asterisk',
            self::MODULE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW => 'fa-asterisk',
            self::MODULE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW => 'fa-asterisk',
        );

        return $fontawesomes[$module[1]] ?? null;
    }
    public function getBodyClass(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_WIDGET_HIGHLIGHTS_SIMPLEVIEW:
            case self::MODULE_WIDGET_HIGHLIGHTS_FULLVIEW:
            case self::MODULE_WIDGET_HIGHLIGHTS_DETAILS:
            case self::MODULE_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_SIMPLEVIEW:
            case self::MODULE_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_FULLVIEW:
            case self::MODULE_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_DETAILS:
            case self::MODULE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW:
                return '';

            case self::MODULE_WIDGET_REFERENCEDBY_DETAILS:
            case self::MODULE_WIDGET_REFERENCEDBY_SIMPLEVIEW:
            case self::MODULE_WIDGET_REFERENCEDBY_FULLVIEW:
            case self::MODULE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_DETAILS:
            case self::MODULE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:
                return 'panel-body';
        }

        return parent::getBodyClass($module, $props);
    }
    public function getItemWrapper(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_WIDGET_HIGHLIGHTS_SIMPLEVIEW:
            case self::MODULE_WIDGET_HIGHLIGHTS_FULLVIEW:
            case self::MODULE_WIDGET_HIGHLIGHTS_DETAILS:
            case self::MODULE_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_SIMPLEVIEW:
            case self::MODULE_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_FULLVIEW:
            case self::MODULE_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_DETAILS:
            case self::MODULE_WIDGET_REFERENCEDBY_DETAILS:
            case self::MODULE_WIDGET_REFERENCEDBY_SIMPLEVIEW:
            case self::MODULE_WIDGET_REFERENCEDBY_FULLVIEW:
            case self::MODULE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_DETAILS:
            case self::MODULE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW:
            case self::MODULE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:
                return '';
        }

        return parent::getItemWrapper($module, $props);
    }
    public function getWidgetClass(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_WIDGET_HIGHLIGHTS_SIMPLEVIEW:
            case self::MODULE_WIDGET_HIGHLIGHTS_FULLVIEW:
            case self::MODULE_WIDGET_HIGHLIGHTS_DETAILS:
            case self::MODULE_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_SIMPLEVIEW:
            case self::MODULE_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_FULLVIEW:
            case self::MODULE_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_DETAILS:
            case self::MODULE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW:
                return '';

            case self::MODULE_WIDGET_REFERENCEDBY_DETAILS:
            case self::MODULE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_DETAILS:
            case self::MODULE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:
                return 'panel panel-default panel-sm';
        }

        return parent::getWidgetClass($module, $props);
    }
    public function getTitleWrapperClass(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_WIDGET_HIGHLIGHTS_SIMPLEVIEW:
            case self::MODULE_WIDGET_HIGHLIGHTS_FULLVIEW:
            case self::MODULE_WIDGET_HIGHLIGHTS_DETAILS:
            case self::MODULE_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_SIMPLEVIEW:
            case self::MODULE_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_FULLVIEW:
            case self::MODULE_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_DETAILS:
            case self::MODULE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW:
                return '';
        }

        return parent::getTitleWrapperClass($module, $props);
    }
    public function getTitleClass(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_WIDGET_HIGHLIGHTS_SIMPLEVIEW:
            case self::MODULE_WIDGET_HIGHLIGHTS_FULLVIEW:
            case self::MODULE_WIDGET_HIGHLIGHTS_DETAILS:
                return '';

            case self::MODULE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW:
                return 'panel-title';
        }

        return parent::getTitleClass($module, $props);
    }
    public function getQuicklinkgroupSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_WIDGET_HIGHLIGHTS_SIMPLEVIEW:
            case self::MODULE_WIDGET_HIGHLIGHTS_FULLVIEW:
            case self::MODULE_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_SIMPLEVIEW:
            case self::MODULE_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_FULLVIEW:
                return [PoP_AddHighlights_Module_Processor_PostButtons::class, PoP_AddHighlights_Module_Processor_PostButtons::MODULE_BUTTON_HIGHLIGHT_CREATEBTN];

            case self::MODULE_WIDGET_REFERENCEDBY_SIMPLEVIEW:
            case self::MODULE_WIDGET_REFERENCEDBY_FULLVIEW:
            case self::MODULE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW:
            case self::MODULE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:
                return [PoP_AddRelatedPosts_Module_Processor_ControlButtonGroups::class, PoP_AddRelatedPosts_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_ADDRELATEDPOST];
        }

        return parent::getQuicklinkgroupSubmodule($module);
    }
    public function collapsible(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_WIDGET_HIGHLIGHTS_DETAILS:
            case self::MODULE_WIDGET_HIGHLIGHTS_APPENDTOSCRIPT_DETAILS:
            case self::MODULE_WIDGET_REFERENCEDBY_DETAILS:
            case self::MODULE_WIDGET_REFERENCEDBY_APPENDTOSCRIPT_DETAILS:
                return true;
        }

        return parent::collapsible($module, $props);
    }
}


