<?php

use PoPCMSSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoPCMSSchema\QueriedObject\ComponentProcessors\QueriedDBObjectComponentProcessorTrait;

class UserStance_Module_Processor_CustomSidebarDataloads extends PoP_Module_Processor_DataloadsBase
{
    use QueriedDBObjectComponentProcessorTrait;

    public final const MODULE_DATALOAD_SINGLE_STANCE_SIDEBAR = 'dataload-single-stance-sidebar';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_SINGLE_STANCE_SIDEBAR],
        );
    }

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        $orientation = \PoP\Root\App::applyFilters(POP_HOOK_BLOCKSIDEBARS_ORIENTATION, 'vertical');
        $vertical = ($orientation == 'vertical');

        $inners = array(
            self::MODULE_DATALOAD_SINGLE_STANCE_SIDEBAR => $vertical ?
                [UserStance_Module_Processor_CustomVerticalSingleSidebars::class, UserStance_Module_Processor_CustomVerticalSingleSidebars::MODULE_VERTICALSIDEBAR_SINGLE_STANCE] :
                [UserStance_Module_Processor_CustomPostLayoutSidebars::class, UserStance_Module_Processor_CustomPostLayoutSidebars::MODULE_LAYOUT_POSTSIDEBAR_HORIZONTAL_STANCE],
        );
        if ($inner = $inners[$component[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    // public function getNature(array $component)
    // {
    //     switch ($component[1]) {
    //         case self::MODULE_DATALOAD_SINGLE_STANCE_SIDEBAR:
    //             return CustomPostRequestNature::CUSTOMPOST;
    //     }

    //     return parent::getNature($component);
    // }

    public function getObjectIDOrIDs(array $component, array &$props, &$data_properties): string | int | array
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_SINGLE_STANCE_SIDEBAR:
                return $this->getQueriedDBObjectID($component, $props, $data_properties);
        }

        return parent::getObjectIDOrIDs($component, $props, $data_properties);
    }

    public function getRelationalTypeResolver(array $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_SINGLE_STANCE_SIDEBAR:
                return CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetObjectTypeResolver();
        }

        return parent::getRelationalTypeResolver($component);
    }
}



