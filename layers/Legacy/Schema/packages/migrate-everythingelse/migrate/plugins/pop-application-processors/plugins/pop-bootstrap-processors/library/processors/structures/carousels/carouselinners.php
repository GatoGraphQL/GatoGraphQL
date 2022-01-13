<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

define('POP_HOOK_CAROUSEL_USERS_GRIDCLASS', 'carousel-users-gridclass');

class PoP_Module_Processor_CustomCarouselInners extends PoP_Module_Processor_CarouselInnersBase
{
    public const MODULE_CAROUSELINNER_USERS = 'carouselinner-users';
    
    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CAROUSELINNER_USERS],
        );
    }

    public function getLayoutGrid(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_CAROUSELINNER_USERS:
                return array(
                    'row-items' => 12,
                    // Allow ThemeStyle Expansive to change the class
                    'class' => \PoP\Root\App::getHookManager()->applyFilters(POP_HOOK_CAROUSEL_USERS_GRIDCLASS, 'col-xs-4 col-sm-2 no-padding'),
                    'divider' => 12
                );
        }

        return parent::getLayoutGrid($module, $props);
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_CAROUSELINNER_USERS:
                $ret[] = [PoP_Module_Processor_CustomPopoverLayouts::class, PoP_Module_Processor_CustomPopoverLayouts::MODULE_LAYOUT_POPOVER_USER_AVATAR];
                break;
        }

        return $ret;
    }
}


