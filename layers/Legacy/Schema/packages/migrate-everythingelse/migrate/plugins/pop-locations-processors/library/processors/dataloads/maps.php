<?php
use PoP\Engine\ComponentProcessors\ObjectIDsFromURLParamComponentProcessorTrait;
use PoPCMSSchema\Locations\TypeResolvers\ObjectType\LocationObjectTypeResolver;

class PoP_Module_Processor_LocationsMapDataloads extends PoP_Module_Processor_DataloadsBase
{
    use ObjectIDsFromURLParamComponentProcessorTrait;

    public final const MODULE_DATALOAD_LOCATIONSMAP = 'dataload-locationsmap';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_LOCATIONSMAP],
        );
    }

    public function getRelevantRoute(array $componentVariation, array &$props): ?string
    {
        return match($componentVariation[1]) {
            self::MODULE_DATALOAD_LOCATIONSMAP => POP_LOCATIONS_ROUTE_LOCATIONSMAP,
            default => parent::getRelevantRoute($componentVariation, $props),
        };
    }

    protected function getInnerSubmodules(array $componentVariation): array
    {
        $ret = parent::getInnerSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_LOCATIONSMAP:
                // $ret[] = [PoP_Locations_Module_Processor_CustomScrolls::class, PoP_Locations_Module_Processor_CustomScrolls::MODULE_SCROLL_STATICIMAGE];
                // $ret[] = [PoP_Module_Processor_MapDivs::class, PoP_Module_Processor_MapDivs::MODULE_MAP_DIV];
                $ret[] = [PoP_Module_Processor_MapDivs::class, PoP_Module_Processor_MapDivs::MODULE_MAPSTATICIMAGE_DIV];
                $ret[] = [PoP_Locations_Module_Processor_CustomScrollMaps::class, PoP_Locations_Module_Processor_CustomScrollMaps::MODULE_SCROLL_LOCATIONS_MAP];
                // $ret[] = [PoP_Module_Processor_MapDrawMarkerScripts::class, PoP_Module_Processor_MapDrawMarkerScripts::MODULE_MAP_SCRIPT_DRAWMARKERS];
                $ret[] = [PoP_Module_Processor_MapDrawMarkerScripts::class, PoP_Module_Processor_MapDrawMarkerScripts::MODULE_MAPSTATICIMAGE_SCRIPT_DRAWMARKERS];
                $ret[] = [PoP_Module_Processor_MapResetMarkerScripts::class, PoP_Module_Processor_MapResetMarkerScripts::MODULE_MAP_SCRIPT_RESETMARKERS];
                break;
        }

        return $ret;
    }

    public function getObjectIDOrIDs(array $componentVariation, array &$props, &$data_properties): string | int | array
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_LOCATIONSMAP:
                return $this->getObjectIDsFromURLParam($componentVariation, $props, $data_properties);
        }
        return parent::getObjectIDOrIDs($componentVariation, $props, $data_properties);
    }

    protected function getObjectIDsParamName(array $componentVariation, array &$props, &$data_properties)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_LOCATIONSMAP:
                return POP_INPUTNAME_LOCATIONID;
        }
        return null;
    }

    public function getRelationalTypeResolver(array $componentVariation): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_LOCATIONSMAP:
                return $this->instanceManager->getInstance(LocationObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($componentVariation);
    }
}



