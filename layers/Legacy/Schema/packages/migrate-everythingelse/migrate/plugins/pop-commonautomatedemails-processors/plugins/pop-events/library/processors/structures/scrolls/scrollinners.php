<?php

class PoPTheme_Wassup_EM_AE_Module_Processor_ScrollInners extends PoP_Module_Processor_ScrollInnersBase
{
    public final const COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_DETAILS = 'scrollinner-automatedemails-events-details';
    public final const COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_SIMPLEVIEW = 'scrollinner-automatedemails-events-simpleview';
    public final const COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_FULLVIEW = 'scrollinner-automatedemails-events-fullview';
    public final const COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_THUMBNAIL = 'scrollinner-automatedemails-events-thumbnail';
    public final const COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_LIST = 'scrollinner-automatedemails-events-list';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_DETAILS,
            self::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_SIMPLEVIEW,
            self::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_FULLVIEW,
            self::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_THUMBNAIL,
            self::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_LIST,
        );
    }

    public function getLayoutGrid(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_THUMBNAIL:

                // Allow ThemeStyle Expansive to override the grid
                return \PoP\Root\App::applyFilters(
                    POP_HOOK_SCROLLINNER_AUTOMATEDEMAILS_THUMBNAIL_GRID,
                    array(
                        'row-items' => 2,
                        'class' => 'col-xsm-6'
                    )
                );

            case self::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_DETAILS:
            case self::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_SIMPLEVIEW:
            case self::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_FULLVIEW:
            case self::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_LIST:

                return array(
                    'row-items' => 1,
                    'class' => 'col-sm-12'
                );
        }

        return parent::getLayoutGrid($component, $props);
    }

    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getLayoutSubcomponents($component);

        $layouts = array(
            self::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_DETAILS => [PoPTheme_Wassup_EM_AE_Module_Processor_PreviewPostLayouts::class, PoPTheme_Wassup_EM_AE_Module_Processor_PreviewPostLayouts::COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_DETAILS],
            self::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_THUMBNAIL => [PoPTheme_Wassup_EM_AE_Module_Processor_PreviewPostLayouts::class, PoPTheme_Wassup_EM_AE_Module_Processor_PreviewPostLayouts::COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_THUMBNAIL],
            self::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_LIST => [PoPTheme_Wassup_EM_AE_Module_Processor_PreviewPostLayouts::class, PoPTheme_Wassup_EM_AE_Module_Processor_PreviewPostLayouts::COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_LIST],
            self::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_SIMPLEVIEW => [PoPTheme_Wassup_EM_AE_Module_Processor_SimpleViewPreviewPostLayouts::class, PoPTheme_Wassup_EM_AE_Module_Processor_SimpleViewPreviewPostLayouts::COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_SIMPLEVIEW],
            self::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_FULLVIEW => [PoPTheme_Wassup_EM_AE_Module_Processor_FullViewLayouts::class, PoPTheme_Wassup_EM_AE_Module_Processor_FullViewLayouts::COMPONENT_LAYOUT_AUTOMATEDEMAILS_FULLVIEW_EVENT],
        );

        if ($layout = $layouts[$component->name] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_DETAILS:
            case self::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_THUMBNAIL:
            case self::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_LIST:
            case self::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_SIMPLEVIEW:
            case self::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_FULLVIEW:
                // Add an extra space at the bottom of each post
                $this->appendProp($component, $props, 'class', 'email-scrollelem-post');
        }

        parent::initModelProps($component, $props);
    }
}


