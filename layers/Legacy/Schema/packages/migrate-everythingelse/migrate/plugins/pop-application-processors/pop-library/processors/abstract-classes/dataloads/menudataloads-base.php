<?php
use PoP\ComponentModel\ComponentProcessors\DataloadingConstants;
use PoPCMSSchema\Menus\Facades\MenuTypeAPIFacade;
use PoPCMSSchema\Menus\TypeResolvers\ObjectType\MenuObjectTypeResolver;

abstract class PoP_Module_Processor_MenuDataloadsBase extends PoP_Module_Processor_DataloadsBase
{
    public function getMenu(array $componentVariation)
    {
        return null;
    }

    protected function getImmutableDataloadQueryArgs(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($componentVariation, $props);

        $ret['menu'] = $this->getMenu($componentVariation);

        return $ret;
    }

    public function getDatasource(array $componentVariation, array &$props): string
    {
        return \PoP\ComponentModel\Constants\DataSources::IMMUTABLE;
    }

    public function getObjectIDOrIDs(array $componentVariation, array &$props, &$data_properties): string | int | array
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
        return parent::getObjectIDOrIDs($componentVariation, $props, $data_properties);
    }

    public function getRelationalTypeResolver(array $componentVariation): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        return $this->instanceManager->getInstance(MenuObjectTypeResolver::class);
    }
}
