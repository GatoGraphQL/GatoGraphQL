<?php
use PoPCMSSchema\QueriedObject\ComponentProcessors\QueriedDBObjectComponentProcessorTrait;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class GD_URE_Module_Processor_CustomSidebarDataloads extends PoP_Module_Processor_DataloadsBase
{
    use QueriedDBObjectComponentProcessorTrait;

    public final const MODULE_DATALOAD_AUTHOR_SIDEBAR_ORGANIZATION = 'dataload-author-sidebar-organization';
    public final const MODULE_DATALOAD_AUTHOR_SIDEBAR_INDIVIDUAL = 'dataload-author-sidebar-individual';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_DATALOAD_AUTHOR_SIDEBAR_ORGANIZATION],
            [self::class, self::COMPONENT_DATALOAD_AUTHOR_SIDEBAR_INDIVIDUAL],
        );
    }

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        $orientation = \PoP\Root\App::applyFilters(POP_HOOK_BLOCKSIDEBARS_ORIENTATION, 'vertical');
        $vertical = ($orientation == 'vertical');

        $block_inners = array(
            self::COMPONENT_DATALOAD_AUTHOR_SIDEBAR_ORGANIZATION => $vertical ?
                [GD_URE_Module_Processor_CustomVerticalAuthorSidebars::class, GD_URE_Module_Processor_CustomVerticalAuthorSidebars::COMPONENT_VERTICALSIDEBAR_AUTHOR_ORGANIZATION] :
                [GD_URE_Module_Processor_CustomUserLayoutSidebars::class, GD_URE_Module_Processor_CustomUserLayoutSidebars::COMPONENT_LAYOUT_USERSIDEBAR_HORIZONTAL_ORGANIZATION],
            self::COMPONENT_DATALOAD_AUTHOR_SIDEBAR_INDIVIDUAL => $vertical ?
                [GD_URE_Module_Processor_CustomVerticalAuthorSidebars::class, GD_URE_Module_Processor_CustomVerticalAuthorSidebars::COMPONENT_VERTICALSIDEBAR_AUTHOR_INDIVIDUAL] :
                [GD_URE_Module_Processor_CustomUserLayoutSidebars::class, GD_URE_Module_Processor_CustomUserLayoutSidebars::COMPONENT_LAYOUT_USERSIDEBAR_HORIZONTAL_INDIVIDUAL],
        );

        if ($block_inner = $block_inners[$component[1]] ?? null) {
            $ret[] = $block_inner;
        }

        return $ret;
    }

    // public function getNature(array $component)
    // {
    //     switch ($component[1]) {
    //         case self::COMPONENT_DATALOAD_AUTHOR_SIDEBAR_ORGANIZATION:
    //         case self::COMPONENT_DATALOAD_AUTHOR_SIDEBAR_INDIVIDUAL:
    //             return UserRequestNature::USER;
    //     }

    //     return parent::getNature($component);
    // }

    public function getObjectIDOrIDs(array $component, array &$props, &$data_properties): string | int | array
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_AUTHOR_SIDEBAR_ORGANIZATION:
            case self::COMPONENT_DATALOAD_AUTHOR_SIDEBAR_INDIVIDUAL:
                return $this->getQueriedDBObjectID($component, $props, $data_properties);
        }

        return parent::getObjectIDOrIDs($component, $props, $data_properties);
    }


    public function getRelationalTypeResolver(array $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_AUTHOR_SIDEBAR_ORGANIZATION:
            case self::COMPONENT_DATALOAD_AUTHOR_SIDEBAR_INDIVIDUAL:
                return $this->instanceManager->getInstance(UserObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($component);
    }
}



