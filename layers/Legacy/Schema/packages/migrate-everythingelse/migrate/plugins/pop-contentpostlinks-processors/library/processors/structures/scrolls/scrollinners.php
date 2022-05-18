<?php

class PoP_ContentPostLinks_Module_Processor_CustomScrollInners extends PoP_Module_Processor_ScrollInnersBase
{
    public final const COMPONENT_SCROLLINNER_LINKS_NAVIGATOR = 'scrollinner-links-navigator';
    public final const COMPONENT_SCROLLINNER_LINKS_ADDONS = 'scrollinner-links-addons';
    public final const COMPONENT_SCROLLINNER_LINKS_DETAILS = 'scrollinner-links-details';
    public final const COMPONENT_SCROLLINNER_LINKS_SIMPLEVIEW = 'scrollinner-links-simpleview';
    public final const COMPONENT_SCROLLINNER_LINKS_FULLVIEW = 'scrollinner-links-fullview';
    public final const COMPONENT_SCROLLINNER_AUTHORLINKS_FULLVIEW = 'scrollinner-authorlinks-fullview';
    public final const COMPONENT_SCROLLINNER_LINKS_THUMBNAIL = 'scrollinner-links-thumbnail';
    public final const COMPONENT_SCROLLINNER_LINKS_LIST = 'scrollinner-links-list';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SCROLLINNER_LINKS_NAVIGATOR],
            [self::class, self::COMPONENT_SCROLLINNER_LINKS_ADDONS],
            [self::class, self::COMPONENT_SCROLLINNER_LINKS_DETAILS],
            [self::class, self::COMPONENT_SCROLLINNER_LINKS_SIMPLEVIEW],
            [self::class, self::COMPONENT_SCROLLINNER_LINKS_FULLVIEW],
            [self::class, self::COMPONENT_SCROLLINNER_LINKS_THUMBNAIL],
            [self::class, self::COMPONENT_SCROLLINNER_LINKS_LIST],
            [self::class, self::COMPONENT_SCROLLINNER_AUTHORLINKS_FULLVIEW],
        );
    }

    public function getLayoutGrid(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_SCROLLINNER_LINKS_THUMBNAIL:
                // Allow ThemeStyle Expansive to override the grid
                return \PoP\Root\App::applyFilters(
                    POP_HOOK_SCROLLINNER_THUMBNAIL_GRID,
                    array(
                        'row-items' => 2,
                        'class' => 'col-xsm-6'
                    )
                );

            case self::COMPONENT_SCROLLINNER_LINKS_NAVIGATOR:
            case self::COMPONENT_SCROLLINNER_LINKS_ADDONS:
            case self::COMPONENT_SCROLLINNER_LINKS_DETAILS:
            case self::COMPONENT_SCROLLINNER_LINKS_SIMPLEVIEW:
            case self::COMPONENT_SCROLLINNER_LINKS_FULLVIEW:
            case self::COMPONENT_SCROLLINNER_LINKS_LIST:
            case self::COMPONENT_SCROLLINNER_AUTHORLINKS_FULLVIEW:
                return array(
                    'row-items' => 1,
                    'class' => 'col-sm-12'
                );
        }

        return parent::getLayoutGrid($component, $props);
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        $layouts = array(
            self::COMPONENT_SCROLLINNER_LINKS_NAVIGATOR => [PoP_ContentPostLinks_Module_Processor_CustomPreviewPostLayouts::class, PoP_ContentPostLinks_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_NAVIGATOR],
            self::COMPONENT_SCROLLINNER_LINKS_ADDONS => [PoP_ContentPostLinks_Module_Processor_CustomPreviewPostLayouts::class, PoP_ContentPostLinks_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_ADDONS],
            self::COMPONENT_SCROLLINNER_LINKS_DETAILS => [PoP_ContentPostLinks_Module_Processor_CustomPreviewPostLayouts::class, PoP_ContentPostLinks_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_DETAILS],
            self::COMPONENT_SCROLLINNER_LINKS_THUMBNAIL => [PoP_ContentPostLinks_Module_Processor_CustomPreviewPostLayouts::class, PoP_ContentPostLinks_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_THUMBNAIL],
            self::COMPONENT_SCROLLINNER_LINKS_LIST => [PoP_ContentPostLinks_Module_Processor_CustomPreviewPostLayouts::class, PoP_ContentPostLinks_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_LIST],
            self::COMPONENT_SCROLLINNER_LINKS_SIMPLEVIEW => [PoP_Module_Processor_CustomSimpleViewPreviewPostLayouts::class, PoP_Module_Processor_CustomSimpleViewPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_SIMPLEVIEW], //[self::class, self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_SIMPLEVIEW],
            self::COMPONENT_SCROLLINNER_LINKS_FULLVIEW => [PoP_ContentPostLinks_Module_Processor_CustomFullViewLayouts::class, PoP_ContentPostLinks_Module_Processor_CustomFullViewLayouts::COMPONENT_LAYOUT_FULLVIEW_LINK],
            self::COMPONENT_SCROLLINNER_AUTHORLINKS_FULLVIEW => [PoP_ContentPostLinks_Module_Processor_CustomFullViewLayouts::class, PoP_ContentPostLinks_Module_Processor_CustomFullViewLayouts::COMPONENT_AUTHORLAYOUT_FULLVIEW_LINK],
        );

        if ($layout = $layouts[$component[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}


