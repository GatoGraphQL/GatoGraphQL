<?php

class GD_EM_Custom_Module_Processor_CustomScrollInners extends PoP_Module_Processor_ScrollInnersBase
{
    public final const MODULE_SCROLLINNER_MYLOCATIONPOSTS_SIMPLEVIEWPREVIEW = 'scrollinner-mylocationposts-simpleviewpreview';
    public final const MODULE_SCROLLINNER_MYLOCATIONPOSTS_FULLVIEWPREVIEW = 'scrollinner-mylocationposts-fullviewpreview';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLLINNER_MYLOCATIONPOSTS_FULLVIEWPREVIEW],
            [self::class, self::MODULE_SCROLLINNER_MYLOCATIONPOSTS_SIMPLEVIEWPREVIEW],
        );
    }

    public function getLayoutGrid(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_SCROLLINNER_MYLOCATIONPOSTS_SIMPLEVIEWPREVIEW:
            case self::MODULE_SCROLLINNER_MYLOCATIONPOSTS_FULLVIEWPREVIEW:
                return array(
                    'row-items' => 1,
                    'class' => 'col-sm-12'
                );
        }

        return parent::getLayoutGrid($componentVariation, $props);
    }

    public function getLayoutSubmodules(array $componentVariation)
    {
        $ret = parent::getLayoutSubmodules($componentVariation);

        $layouts = array(
            self::MODULE_SCROLLINNER_MYLOCATIONPOSTS_SIMPLEVIEWPREVIEW => [PoPSFEM_Module_Processor_SimpleViewPreviewPostLayouts::class, PoPSFEM_Module_Processor_SimpleViewPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_SIMPLEVIEW],
            self::MODULE_SCROLLINNER_MYLOCATIONPOSTS_FULLVIEWPREVIEW => [GD_Custom_EM_Module_Processor_CustomFullViewLayouts::class, GD_Custom_EM_Module_Processor_CustomFullViewLayouts::MODULE_LAYOUT_FULLVIEW_LOCATIONPOST],
        );

        if ($layout = $layouts[$componentVariation[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}


