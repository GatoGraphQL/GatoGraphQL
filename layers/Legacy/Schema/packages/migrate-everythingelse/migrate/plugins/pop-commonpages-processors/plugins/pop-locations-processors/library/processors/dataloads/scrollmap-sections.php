<?php
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class GD_CommonPages_EM_Module_Processor_CustomScrollMapSectionDataloads extends GD_EM_Module_Processor_ScrollMapDataloadsBase
{
    public final const MODULE_DATALOAD_WHOWEARE_SCROLLMAP = 'dataload-whoweare-scrollmap';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_WHOWEARE_SCROLLMAP],
        );
    }

    public function getInnerSubmodule(array $componentVariation)
    {
        $inner_componentVariations = array(
            self::MODULE_DATALOAD_WHOWEARE_SCROLLMAP => [GD_CommonPages_EM_Module_Processor_CustomScrollMapSections::class, GD_CommonPages_EM_Module_Processor_CustomScrollMapSections::MODULE_SCROLLMAP_WHOWEARE_SCROLLMAP],
        );

        return $inner_componentVariations[$componentVariation[1]] ?? null;
    }

    protected function showFetchmore(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_WHOWEARE_SCROLLMAP:
                return false;
        }

        return parent::showFetchmore($componentVariation);
    }

    public function getFormat(array $componentVariation): ?string
    {
        $maps = array(
            [self::class, self::MODULE_DATALOAD_WHOWEARE_SCROLLMAP],
        );
        if (in_array($componentVariation, $maps)) {
            $format = POP_FORMAT_MAP;
        }

        return $format ?? parent::getFormat($componentVariation);
    }

    public function getRelationalTypeResolver(array $componentVariation): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_WHOWEARE_SCROLLMAP:
                return $this->instanceManager->getInstance(UserObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($componentVariation);
    }

    public function getDatasource(array $componentVariation, array &$props): string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_WHOWEARE_SCROLLMAP:
                return \PoP\ComponentModel\Constants\DataSources::IMMUTABLE;
        }

        return parent::getDatasource($componentVariation, $props);
    }

    public function getObjectIDOrIDs(array $componentVariation, array &$props, &$data_properties): string | int | array
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_WHOWEARE_SCROLLMAP:
                return getWhoweareCoreUserIds();
        }

        return parent::getObjectIDOrIDs($componentVariation, $props, $data_properties);
    }
}



