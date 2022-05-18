<?php

class GD_EM_Custom_Module_Processor_CustomScrollInners extends PoP_Module_Processor_ScrollInnersBase
{
    public final const COMPONENT_SCROLLINNER_MYLOCATIONPOSTS_SIMPLEVIEWPREVIEW = 'scrollinner-mylocationposts-simpleviewpreview';
    public final const COMPONENT_SCROLLINNER_MYLOCATIONPOSTS_FULLVIEWPREVIEW = 'scrollinner-mylocationposts-fullviewpreview';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SCROLLINNER_MYLOCATIONPOSTS_FULLVIEWPREVIEW],
            [self::class, self::COMPONENT_SCROLLINNER_MYLOCATIONPOSTS_SIMPLEVIEWPREVIEW],
        );
    }

    public function getLayoutGrid(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_SCROLLINNER_MYLOCATIONPOSTS_SIMPLEVIEWPREVIEW:
            case self::COMPONENT_SCROLLINNER_MYLOCATIONPOSTS_FULLVIEWPREVIEW:
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
            self::COMPONENT_SCROLLINNER_MYLOCATIONPOSTS_SIMPLEVIEWPREVIEW => [PoPSFEM_Module_Processor_SimpleViewPreviewPostLayouts::class, PoPSFEM_Module_Processor_SimpleViewPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_SIMPLEVIEW],
            self::COMPONENT_SCROLLINNER_MYLOCATIONPOSTS_FULLVIEWPREVIEW => [GD_Custom_EM_Module_Processor_CustomFullViewLayouts::class, GD_Custom_EM_Module_Processor_CustomFullViewLayouts::COMPONENT_LAYOUT_FULLVIEW_LOCATIONPOST],
        );

        if ($layout = $layouts[$component[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}


