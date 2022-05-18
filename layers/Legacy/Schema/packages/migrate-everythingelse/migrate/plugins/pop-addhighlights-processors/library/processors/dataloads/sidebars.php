<?php
use PoPCMSSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoPCMSSchema\QueriedObject\ComponentProcessors\QueriedDBObjectComponentProcessorTrait;

class PoP_AddHighlights_Module_Processor_CustomSidebarDataloads extends PoP_Module_Processor_DataloadsBase
{
    use QueriedDBObjectComponentProcessorTrait;

    public final const MODULE_DATALOAD_SINGLE_HIGHLIGHT_SIDEBAR = 'dataload-single-highlight-sidebar';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_SINGLE_HIGHLIGHT_SIDEBAR],
        );
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        $orientation = \PoP\Root\App::applyFilters(POP_HOOK_BLOCKSIDEBARS_ORIENTATION, 'vertical');
        $vertical = ($orientation == 'vertical');
        $inners = array(
            self::MODULE_DATALOAD_SINGLE_HIGHLIGHT_SIDEBAR => $vertical ?
                [Wassup_Module_Processor_CustomVerticalSingleSidebars::class, Wassup_Module_Processor_CustomVerticalSingleSidebars::MODULE_VERTICALSIDEBAR_SINGLE_HIGHLIGHT] :
                [PoP_Module_Processor_CustomPostLayoutSidebars::class, PoP_Module_Processor_CustomPostLayoutSidebars::MODULE_LAYOUT_POSTSIDEBAR_HORIZONTAL_HIGHLIGHT],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getObjectIDOrIDs(array $module, array &$props, &$data_properties): string | int | array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_SINGLE_HIGHLIGHT_SIDEBAR:
                return $this->getQueriedDBObjectID($module, $props, $data_properties);
        }

        return parent::getObjectIDOrIDs($module, $props, $data_properties);
    }

    // public function getNature(array $module)
    // {
    //     switch ($module[1]) {
    //         case self::MODULE_DATALOAD_SINGLE_HIGHLIGHT_SIDEBAR:
    //             return CustomPostRequestNature::CUSTOMPOST;
    //     }

    //     return parent::getNature($module);
    // }

    public function getRelationalTypeResolver(array $module): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_SINGLE_HIGHLIGHT_SIDEBAR:
                return CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetObjectTypeResolver();
        }

        return parent::getRelationalTypeResolver($module);
    }
}



