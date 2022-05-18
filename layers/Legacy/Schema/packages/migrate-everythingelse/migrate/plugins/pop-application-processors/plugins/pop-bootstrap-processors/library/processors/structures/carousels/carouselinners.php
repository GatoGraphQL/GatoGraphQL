<?php

define('POP_HOOK_CAROUSEL_USERS_GRIDCLASS', 'carousel-users-gridclass');

class PoP_Module_Processor_CustomCarouselInners extends PoP_Module_Processor_CarouselInnersBase
{
    public final const COMPONENT_CAROUSELINNER_USERS = 'carouselinner-users';
    
    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_CAROUSELINNER_USERS],
        );
    }

    public function getLayoutGrid(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_CAROUSELINNER_USERS:
                return array(
                    'row-items' => 12,
                    // Allow ThemeStyle Expansive to change the class
                    'class' => \PoP\Root\App::applyFilters(POP_HOOK_CAROUSEL_USERS_GRIDCLASS, 'col-xs-4 col-sm-2 no-padding'),
                    'divider' => 12
                );
        }

        return parent::getLayoutGrid($component, $props);
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_CAROUSELINNER_USERS:
                $ret[] = [PoP_Module_Processor_CustomPopoverLayouts::class, PoP_Module_Processor_CustomPopoverLayouts::COMPONENT_LAYOUT_POPOVER_USER_AVATAR];
                break;
        }

        return $ret;
    }
}


