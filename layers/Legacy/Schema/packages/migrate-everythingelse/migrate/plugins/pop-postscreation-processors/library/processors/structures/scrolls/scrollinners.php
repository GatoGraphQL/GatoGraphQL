<?php

class PoP_ContentPostLinksCreation_Module_Processor_CustomScrollInners extends PoP_Module_Processor_ScrollInnersBase
{
    public final const MODULE_SCROLLINNER_MYLINKS_SIMPLEVIEWPREVIEW = 'scrollinner-mylinks-simpleviewpreview';
    public final const MODULE_SCROLLINNER_MYLINKS_FULLVIEWPREVIEW = 'scrollinner-mylinks-fullviewpreview';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLLINNER_MYLINKS_SIMPLEVIEWPREVIEW],
            [self::class, self::MODULE_SCROLLINNER_MYLINKS_FULLVIEWPREVIEW],
        );
    }

    public function getLayoutGrid(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_SCROLLINNER_MYLINKS_SIMPLEVIEWPREVIEW:
            case self::MODULE_SCROLLINNER_MYLINKS_FULLVIEWPREVIEW:
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
            self::MODULE_SCROLLINNER_MYLINKS_SIMPLEVIEWPREVIEW => [PoP_Module_Processor_CustomSimpleViewPreviewPostLayouts::class, PoP_Module_Processor_CustomSimpleViewPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_SIMPLEVIEW],
            self::MODULE_SCROLLINNER_MYLINKS_FULLVIEWPREVIEW => [PoP_ContentPostLinks_Module_Processor_CustomFullViewLayouts::class, PoP_ContentPostLinks_Module_Processor_CustomFullViewLayouts::MODULE_LAYOUT_FULLVIEW_LINK],
        );

        if ($layout = $layouts[$componentVariation[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}


