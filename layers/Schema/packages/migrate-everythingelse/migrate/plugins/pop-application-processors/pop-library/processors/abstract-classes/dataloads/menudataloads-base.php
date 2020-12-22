<?php
use PoP\ComponentModel\ModuleProcessors\DataloadingConstants;
use PoPSchema\Menus\TypeResolvers\MenuTypeResolver;
use PoPSchema\Menus\Misc\MenuHelpers;

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
        return POP_DATALOAD_DATASOURCE_IMMUTABLE;
    }

    public function getDBObjectIDOrIDs(array $module, array &$props, &$data_properties)
    {
        $query_args = $data_properties[DataloadingConstants::QUERYARGS];
        if ($menu = $query_args['menu']) {
            return MenuHelpers::getMenuIDFromMenuName($menu);
        }
        return parent::getDBObjectIDOrIDs($module, $props, $data_properties);
    }

    public function getTypeResolverClass(array $module): ?string
    {
        return MenuTypeResolver::class;
    }
}
