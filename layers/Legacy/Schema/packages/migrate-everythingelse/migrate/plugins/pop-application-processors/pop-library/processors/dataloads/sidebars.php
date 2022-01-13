<?php
use PoPSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoPSchema\PostTags\TypeResolvers\ObjectType\PostTagObjectTypeResolver;
use PoPSchema\QueriedObject\ModuleProcessors\QueriedDBObjectModuleProcessorTrait;

class PoP_Module_Processor_CustomSidebarDataloads extends PoP_Module_Processor_DataloadsBase
{
    use QueriedDBObjectModuleProcessorTrait;

    public const MODULE_DATALOAD_TAG_SIDEBAR = 'dataload-tag-sidebar';
    public const MODULE_DATALOAD_SINGLE_POST_SIDEBAR = 'dataload-single-post-sidebar';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_TAG_SIDEBAR],
            [self::class, self::MODULE_DATALOAD_SINGLE_POST_SIDEBAR],
        );
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        $orientation = \PoP\Root\App::getHookManager()->applyFilters(POP_HOOK_BLOCKSIDEBARS_ORIENTATION, 'vertical');
        $vertical = ($orientation == 'vertical');

        $block_inners = array(
            self::MODULE_DATALOAD_TAG_SIDEBAR => $vertical ?
                [Wassup_Module_Processor_CustomVerticalTagSidebars::class, Wassup_Module_Processor_CustomVerticalTagSidebars::MODULE_VERTICALSIDEBAR_TAG] :
                [PoP_Module_Processor_CustomTagLayoutSidebars::class, PoP_Module_Processor_CustomTagLayoutSidebars::MODULE_LAYOUT_TAGSIDEBAR_HORIZONTAL],
            self::MODULE_DATALOAD_SINGLE_POST_SIDEBAR => $vertical ?
                [Wassup_Module_Processor_CustomVerticalSingleSidebars::class, Wassup_Module_Processor_CustomVerticalSingleSidebars::MODULE_VERTICALSIDEBAR_SINGLE_POST] :
                [PoP_Module_Processor_CustomPostLayoutSidebars::class, PoP_Module_Processor_CustomPostLayoutSidebars::MODULE_LAYOUT_POSTSIDEBAR_HORIZONTAL_POST],
        );

        if ($block_inner = $block_inners[$module[1]] ?? null) {
            $ret[] = $block_inner;
        }

        return $ret;
    }

    // public function getNature(array $module)
    // {
    //     switch ($module[1]) {
    //         case self::MODULE_DATALOAD_TAG_SIDEBAR:
    //             return TagRouteNatures::TAG;

    //         case self::MODULE_DATALOAD_SINGLE_POST_SIDEBAR:
    //             return CustomPostRouteNatures::CUSTOMPOST;
    //     }

    //     return parent::getNature($module);
    // }

    public function getObjectIDOrIDs(array $module, array &$props, &$data_properties): string | int | array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_SINGLE_POST_SIDEBAR:
            case self::MODULE_DATALOAD_TAG_SIDEBAR:
                return $this->getQueriedDBObjectID($module, $props, $data_properties);
        }

        return parent::getObjectIDOrIDs($module, $props, $data_properties);
    }

    public function getRelationalTypeResolver(array $module): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_TAG_SIDEBAR:
                return $this->instanceManager->getInstance(PostTagObjectTypeResolver::class);

            case self::MODULE_DATALOAD_SINGLE_POST_SIDEBAR:
                return CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetObjectTypeResolver();
        }

        return parent::getRelationalTypeResolver($module);
    }
}



