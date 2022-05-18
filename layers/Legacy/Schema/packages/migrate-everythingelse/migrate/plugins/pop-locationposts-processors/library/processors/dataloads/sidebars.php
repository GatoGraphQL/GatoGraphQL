<?php
use PoPCMSSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoPCMSSchema\QueriedObject\ComponentProcessors\QueriedDBObjectComponentProcessorTrait;

class PoP_LocationPosts_Module_Processor_CustomSidebarDataloads extends PoP_Module_Processor_DataloadsBase
{
    use QueriedDBObjectComponentProcessorTrait;

    public final const MODULE_DATALOAD_SINGLE_LOCATIONPOST_SIDEBAR = 'dataload-single-locationpost-sidebar';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_DATALOAD_SINGLE_LOCATIONPOST_SIDEBAR],
        );
    }

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        $orientation = \PoP\Root\App::applyFilters(POP_HOOK_BLOCKSIDEBARS_ORIENTATION, 'vertical');
        $vertical = ($orientation == 'vertical');
        $inners = array(
            self::COMPONENT_DATALOAD_SINGLE_LOCATIONPOST_SIDEBAR => $vertical ?
                [GD_SP_EM_Module_Processor_CustomVerticalSingleSidebars::class, GD_SP_EM_Module_Processor_CustomVerticalSingleSidebars::COMPONENT_VERTICALSIDEBAR_SINGLE_LOCATIONPOST] :
                [GD_Custom_EM_Module_Processor_CustomPostLayoutSidebars::class, GD_Custom_EM_Module_Processor_CustomPostLayoutSidebars::COMPONENT_LAYOUT_POSTSIDEBAR_HORIZONTAL_LOCATIONPOST],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getObjectIDOrIDs(array $component, array &$props, &$data_properties): string | int | array
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_SINGLE_LOCATIONPOST_SIDEBAR:
                return $this->getQueriedDBObjectID($component, $props, $data_properties);
        }

        return parent::getObjectIDOrIDs($component, $props, $data_properties);
    }

    // public function getNature(array $component)
    // {
    //     switch ($component[1]) {
    //         case self::COMPONENT_DATALOAD_SINGLE_LOCATIONPOST_SIDEBAR:
    //             return CustomPostRequestNature::CUSTOMPOST;
    //     }

    //     return parent::getNature($component);
    // }

    public function getRelationalTypeResolver(array $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_SINGLE_LOCATIONPOST_SIDEBAR:
                return CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetObjectTypeResolver();
        }

        return parent::getRelationalTypeResolver($component);
    }
}



