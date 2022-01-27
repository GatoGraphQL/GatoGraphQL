<?php
use PoP\ComponentModel\ModuleProcessors\DataloadingConstants;
use PoPCMSSchema\Menus\Facades\MenuTypeAPIFacade;
use PoPCMSSchema\Menus\TypeResolvers\ObjectType\MenuObjectTypeResolver;

abstract class PoP_Module_Processor_MenuDataloadsBase extends PoP_Module_Processor_DataloadsBase
{
    public function getMenu(array $module)
    {
        return null;
    }

    protected function getImmutableDataloadQueryArgs(array $module, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($module, $props);

        $ret['menu'] = $this->getMenu($module);

        return $ret;
    }

    public function getDatasource(array $module, array &$props): string
    {
        return \PoP\ComponentModel\Constants\DataSources::IMMUTABLE;
    }

    public function getObjectIDOrIDs(array $module, array &$props, &$data_properties): string | int | array
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
        return parent::getObjectIDOrIDs($module, $props, $data_properties);
    }

    public function getRelationalTypeResolver(array $module): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        return $this->instanceManager->getInstance(MenuObjectTypeResolver::class);
    }
}
