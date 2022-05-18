<?php

class UserStance_Module_Processor_CustomCarouselInners extends PoP_Module_Processor_CarouselInnersBase
{
    public final const COMPONENT_CAROUSELINNER_STANCES = 'carouselinner-stances';
    public final const COMPONENT_CAROUSELINNER_AUTHORSTANCES = 'carouselinner-authorstances';
    public final const COMPONENT_CAROUSELINNER_TAGSTANCES = 'carouselinner-tagstances';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_CAROUSELINNER_STANCES],
            [self::class, self::COMPONENT_CAROUSELINNER_AUTHORSTANCES],
            [self::class, self::COMPONENT_CAROUSELINNER_TAGSTANCES],
        );
    }

    public function getLayoutGrid(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_CAROUSELINNER_STANCES:
            case self::COMPONENT_CAROUSELINNER_AUTHORSTANCES:
            case self::COMPONENT_CAROUSELINNER_TAGSTANCES:
                return array(
                    'row-items' => 1,
                    'class' => 'col-sm-12',
                    'divider' => 1
                );
        }

        return parent::getLayoutGrid($component, $props);
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_CAROUSELINNER_STANCES:
            case self::COMPONENT_CAROUSELINNER_AUTHORSTANCES:
            case self::COMPONENT_CAROUSELINNER_TAGSTANCES:
                $ret[] = [UserStance_Module_Processor_CustomPreviewPostLayouts::class, UserStance_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHORREFERENCED];
                break;
                
         // case self::COMPONENT_CAROUSELINNER_AUTHORSTANCES:

         //     $ret[] = [UserStance_Module_Processor_CustomPreviewPostLayouts::class, UserStance_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_CONTENTREFERENCED];
         //     break;
        }

        return $ret;
    }
}


