<?php
use PoPSchema\Users\TypeResolvers\UserTypeResolver;

class GD_CommonPages_EM_Module_Processor_CustomScrollMapSectionDataloads extends GD_EM_Module_Processor_ScrollMapDataloadsBase
{
    public const MODULE_DATALOAD_WHOWEARE_SCROLLMAP = 'dataload-whoweare-scrollmap';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_WHOWEARE_SCROLLMAP],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        $inner_modules = array(
            self::MODULE_DATALOAD_WHOWEARE_SCROLLMAP => [GD_CommonPages_EM_Module_Processor_CustomScrollMapSections::class, GD_CommonPages_EM_Module_Processor_CustomScrollMapSections::MODULE_SCROLLMAP_WHOWEARE_SCROLLMAP],
        );

        return $inner_modules[$module[1]] ?? null;
    }

    protected function showFetchmore(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_WHOWEARE_SCROLLMAP:
                return false;
        }

        return parent::showFetchmore($module);
    }

    public function getFormat(array $module): ?string
    {
        $maps = array(
            [self::class, self::MODULE_DATALOAD_WHOWEARE_SCROLLMAP],
        );
        if (in_array($module, $maps)) {
            $format = POP_FORMAT_MAP;
        }

        return $format ?? parent::getFormat($module);
    }

    public function getTypeResolverClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_WHOWEARE_SCROLLMAP:
                return UserTypeResolver::class;
        }

        return parent::getTypeResolverClass($module);
    }

    public function getDatasource(array $module, array &$props): string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_WHOWEARE_SCROLLMAP:
                return \PoP\ComponentModel\Constants\DataSources::IMMUTABLE;
        }

        return parent::getDatasource($module, $props);
    }

    public function getDBObjectIDOrIDs(array $module, array &$props, &$data_properties): string | int | array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_WHOWEARE_SCROLLMAP:
                return getWhoweareCoreUserIds();
        }

        return parent::getDBObjectIDOrIDs($module, $props, $data_properties);
    }
}



