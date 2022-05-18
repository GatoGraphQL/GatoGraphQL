<?php

class UserStance_Module_Processor_CustomCarouselInners extends PoP_Module_Processor_CarouselInnersBase
{
    public final const MODULE_CAROUSELINNER_STANCES = 'carouselinner-stances';
    public final const MODULE_CAROUSELINNER_AUTHORSTANCES = 'carouselinner-authorstances';
    public final const MODULE_CAROUSELINNER_TAGSTANCES = 'carouselinner-tagstances';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CAROUSELINNER_STANCES],
            [self::class, self::MODULE_CAROUSELINNER_AUTHORSTANCES],
            [self::class, self::MODULE_CAROUSELINNER_TAGSTANCES],
        );
    }

    public function getLayoutGrid(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_CAROUSELINNER_STANCES:
            case self::MODULE_CAROUSELINNER_AUTHORSTANCES:
            case self::MODULE_CAROUSELINNER_TAGSTANCES:
                return array(
                    'row-items' => 1,
                    'class' => 'col-sm-12',
                    'divider' => 1
                );
        }

        return parent::getLayoutGrid($module, $props);
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_CAROUSELINNER_STANCES:
            case self::MODULE_CAROUSELINNER_AUTHORSTANCES:
            case self::MODULE_CAROUSELINNER_TAGSTANCES:
                $ret[] = [UserStance_Module_Processor_CustomPreviewPostLayouts::class, UserStance_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHORREFERENCED];
                break;
                
         // case self::MODULE_CAROUSELINNER_AUTHORSTANCES:

         //     $ret[] = [UserStance_Module_Processor_CustomPreviewPostLayouts::class, UserStance_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_STANCE_CONTENTREFERENCED];
         //     break;
        }

        return $ret;
    }
}


