<?php
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class GD_CommonPages_EM_Module_Processor_CustomScrollMapSectionDataloads extends GD_EM_Module_Processor_ScrollMapDataloadsBase
{
    public final const MODULE_DATALOAD_WHOWEARE_SCROLLMAP = 'dataload-whoweare-scrollmap';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_WHOWEARE_SCROLLMAP],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        $inner_components = array(
            self::MODULE_DATALOAD_WHOWEARE_SCROLLMAP => [GD_CommonPages_EM_Module_Processor_CustomScrollMapSections::class, GD_CommonPages_EM_Module_Processor_CustomScrollMapSections::MODULE_SCROLLMAP_WHOWEARE_SCROLLMAP],
        );

        return $inner_components[$component[1]] ?? null;
    }

    protected function showFetchmore(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_WHOWEARE_SCROLLMAP:
                return false;
        }

        return parent::showFetchmore($component);
    }

    public function getFormat(array $component): ?string
    {
        $maps = array(
            [self::class, self::MODULE_DATALOAD_WHOWEARE_SCROLLMAP],
        );
        if (in_array($component, $maps)) {
            $format = POP_FORMAT_MAP;
        }

        return $format ?? parent::getFormat($component);
    }

    public function getRelationalTypeResolver(array $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_WHOWEARE_SCROLLMAP:
                return $this->instanceManager->getInstance(UserObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($component);
    }

    public function getDatasource(array $component, array &$props): string
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_WHOWEARE_SCROLLMAP:
                return \PoP\ComponentModel\Constants\DataSources::IMMUTABLE;
        }

        return parent::getDatasource($component, $props);
    }

    public function getObjectIDOrIDs(array $component, array &$props, &$data_properties): string | int | array
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_WHOWEARE_SCROLLMAP:
                return getWhoweareCoreUserIds();
        }

        return parent::getObjectIDOrIDs($component, $props, $data_properties);
    }
}



