<?php
use PoP\ComponentModel\ComponentProcessors\DataloadingConstants;
use PoPCMSSchema\Menus\Facades\MenuTypeAPIFacade;
use PoPCMSSchema\Menus\TypeResolvers\ObjectType\MenuObjectTypeResolver;

abstract class PoP_Module_Processor_MenuDataloadsBase extends PoP_Module_Processor_DataloadsBase
{
    public function getMenu(\PoP\ComponentModel\Component\Component $component)
    {
        return null;
    }

    protected function getImmutableDataloadQueryArgs(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($component, $props);

        $ret['menu'] = $this->getMenu($component);

        return $ret;
    }

    public function getDatasource(\PoP\ComponentModel\Component\Component $component, array &$props): string
    {
        return \PoP\ComponentModel\Constants\DataSources::IMMUTABLE;
    }

    public function getObjectIDOrIDs(\PoP\ComponentModel\Component\Component $component, array &$props, &$data_properties): string|int|array
    {
        $query_args = $data_properties[DataloadingConstants::QUERYARGS];
        if ($menuName = $query_args['menu']) {
            $menuTypeAPI = MenuTypeAPIFacade::getInstance();
            $menuID = $menuTypeAPI->getMenuIDFromMenuName($menuName);
            if ($menuID === null) {
                return [];
            }
            return $menuID;
        }
        return parent::getObjectIDOrIDs($component, $props, $data_properties);
    }

    public function getRelationalTypeResolver(\PoP\ComponentModel\Component\Component $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        return $this->instanceManager->getInstance(MenuObjectTypeResolver::class);
    }
}
