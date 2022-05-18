<?php
use PoP\Engine\ComponentProcessors\ObjectIDsFromURLParamComponentProcessorTrait;
use PoPCMSSchema\Locations\TypeResolvers\ObjectType\LocationObjectTypeResolver;

class PoP_Module_Processor_LocationsMapDataloads extends PoP_Module_Processor_DataloadsBase
{
    use ObjectIDsFromURLParamComponentProcessorTrait;

    public final const MODULE_DATALOAD_LOCATIONSMAP = 'dataload-locationsmap';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_LOCATIONSMAP],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::MODULE_DATALOAD_LOCATIONSMAP => POP_LOCATIONS_ROUTE_LOCATIONSMAP,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        switch ($component[1]) {
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

    public function getObjectIDOrIDs(array $component, array &$props, &$data_properties): string | int | array
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_LOCATIONSMAP:
                return $this->getObjectIDsFromURLParam($component, $props, $data_properties);
        }
        return parent::getObjectIDOrIDs($component, $props, $data_properties);
    }

    protected function getObjectIDsParamName(array $component, array &$props, &$data_properties)
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_LOCATIONSMAP:
                return POP_INPUTNAME_LOCATIONID;
        }
        return null;
    }

    public function getRelationalTypeResolver(array $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_LOCATIONSMAP:
                return $this->instanceManager->getInstance(LocationObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($component);
    }
}



