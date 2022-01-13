<?php
use PoPSchema\QueriedObject\ModuleProcessors\QueriedDBObjectModuleProcessorTrait;
use PoPSchema\Users\Routing\RouteNatures as UserRouteNatures;
use PoPSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class GD_URE_Module_Processor_CustomSidebarDataloads extends PoP_Module_Processor_DataloadsBase
{
    use QueriedDBObjectModuleProcessorTrait;

    public const MODULE_DATALOAD_AUTHOR_SIDEBAR_ORGANIZATION = 'dataload-author-sidebar-organization';
    public const MODULE_DATALOAD_AUTHOR_SIDEBAR_INDIVIDUAL = 'dataload-author-sidebar-individual';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_AUTHOR_SIDEBAR_ORGANIZATION],
            [self::class, self::MODULE_DATALOAD_AUTHOR_SIDEBAR_INDIVIDUAL],
        );
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        $orientation = \PoP\Root\App::getHookManager()->applyFilters(POP_HOOK_BLOCKSIDEBARS_ORIENTATION, 'vertical');
        $vertical = ($orientation == 'vertical');

        $block_inners = array(
            self::MODULE_DATALOAD_AUTHOR_SIDEBAR_ORGANIZATION => $vertical ?
                [GD_URE_Module_Processor_CustomVerticalAuthorSidebars::class, GD_URE_Module_Processor_CustomVerticalAuthorSidebars::MODULE_VERTICALSIDEBAR_AUTHOR_ORGANIZATION] :
                [GD_URE_Module_Processor_CustomUserLayoutSidebars::class, GD_URE_Module_Processor_CustomUserLayoutSidebars::MODULE_LAYOUT_USERSIDEBAR_HORIZONTAL_ORGANIZATION],
            self::MODULE_DATALOAD_AUTHOR_SIDEBAR_INDIVIDUAL => $vertical ?
                [GD_URE_Module_Processor_CustomVerticalAuthorSidebars::class, GD_URE_Module_Processor_CustomVerticalAuthorSidebars::MODULE_VERTICALSIDEBAR_AUTHOR_INDIVIDUAL] :
                [GD_URE_Module_Processor_CustomUserLayoutSidebars::class, GD_URE_Module_Processor_CustomUserLayoutSidebars::MODULE_LAYOUT_USERSIDEBAR_HORIZONTAL_INDIVIDUAL],
        );

        if ($block_inner = $block_inners[$module[1]] ?? null) {
            $ret[] = $block_inner;
        }

        return $ret;
    }

    // public function getNature(array $module)
    // {
    //     switch ($module[1]) {
    //         case self::MODULE_DATALOAD_AUTHOR_SIDEBAR_ORGANIZATION:
    //         case self::MODULE_DATALOAD_AUTHOR_SIDEBAR_INDIVIDUAL:
    //             return UserRouteNatures::USER;
    //     }

    //     return parent::getNature($module);
    // }

    public function getObjectIDOrIDs(array $module, array &$props, &$data_properties): string | int | array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_AUTHOR_SIDEBAR_ORGANIZATION:
            case self::MODULE_DATALOAD_AUTHOR_SIDEBAR_INDIVIDUAL:
                return $this->getQueriedDBObjectID($module, $props, $data_properties);
        }

        return parent::getObjectIDOrIDs($module, $props, $data_properties);
    }


    public function getRelationalTypeResolver(array $module): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_AUTHOR_SIDEBAR_ORGANIZATION:
            case self::MODULE_DATALOAD_AUTHOR_SIDEBAR_INDIVIDUAL:
                return $this->instanceManager->getInstance(UserObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($module);
    }
}



