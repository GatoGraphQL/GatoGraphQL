<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoPSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoPSchema\CustomPosts\TypeResolvers\CustomPostUnionTypeResolver;
use PoPSchema\QueriedObject\ModuleProcessors\QueriedDBObjectModuleProcessorTrait;

class PoP_LocationPosts_Module_Processor_CustomSidebarDataloads extends PoP_Module_Processor_DataloadsBase
{
    use QueriedDBObjectModuleProcessorTrait;

    public const MODULE_DATALOAD_SINGLE_LOCATIONPOST_SIDEBAR = 'dataload-single-locationpost-sidebar';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_SINGLE_LOCATIONPOST_SIDEBAR],
        );
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        $orientation = HooksAPIFacade::getInstance()->applyFilters(POP_HOOK_BLOCKSIDEBARS_ORIENTATION, 'vertical');
        $vertical = ($orientation == 'vertical');
        $inners = array(
            self::MODULE_DATALOAD_SINGLE_LOCATIONPOST_SIDEBAR => $vertical ?
                [GD_SP_EM_Module_Processor_CustomVerticalSingleSidebars::class, GD_SP_EM_Module_Processor_CustomVerticalSingleSidebars::MODULE_VERTICALSIDEBAR_SINGLE_LOCATIONPOST] :
                [GD_Custom_EM_Module_Processor_CustomPostLayoutSidebars::class, GD_Custom_EM_Module_Processor_CustomPostLayoutSidebars::MODULE_LAYOUT_POSTSIDEBAR_HORIZONTAL_LOCATIONPOST],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getDBObjectIDOrIDs(array $module, array &$props, &$data_properties)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_SINGLE_LOCATIONPOST_SIDEBAR:
                return $this->getQueriedDBObjectID($module, $props, $data_properties);
        }

        return parent::getDBObjectIDOrIDs($module, $props, $data_properties);
    }

    // public function getNature(array $module)
    // {
    //     switch ($module[1]) {
    //         case self::MODULE_DATALOAD_SINGLE_LOCATIONPOST_SIDEBAR:
    //             return CustomPostRouteNatures::CUSTOMPOST;
    //     }

    //     return parent::getNature($module);
    // }

    public function getTypeResolverClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_SINGLE_LOCATIONPOST_SIDEBAR:
                return CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetTypeResolverClass(CustomPostUnionTypeResolver::class);
        }

        return parent::getTypeResolverClass($module);
    }
}



