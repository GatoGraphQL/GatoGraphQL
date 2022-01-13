<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_UserCommunities_Module_Processor_CustomCarouselInners extends PoP_Module_Processor_CarouselInnersBase
{
    public const MODULE_CAROUSELINNER_AUTHORMEMBERS = 'carouselinner-authormembers';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CAROUSELINNER_AUTHORMEMBERS],
        );
    }

    public function getLayoutGrid(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_CAROUSELINNER_AUTHORMEMBERS:
                return array(
                    'row-items' => 12,
                    // Allow ThemeStyle Expansive to change the class
                    'class' => HooksAPIFacade::getInstance()->applyFilters(POP_HOOK_CAROUSEL_USERS_GRIDCLASS, 'col-xs-4 col-sm-2 no-padding'),
                    'divider' => 12
                );
        }

        return parent::getLayoutGrid($module, $props);
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_CAROUSELINNER_AUTHORMEMBERS:
                $ret[] = [PoP_Module_Processor_CustomPopoverLayouts::class, PoP_Module_Processor_CustomPopoverLayouts::MODULE_LAYOUT_POPOVER_USER_AVATAR];
                break;
        }

        return $ret;
    }
}


