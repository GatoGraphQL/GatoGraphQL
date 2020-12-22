<?php
use PoP\ComponentModel\ItemProcessors\ProcessorItemUtils;
use PoP\Resources\DefinitionGroups;

class ResourceUtils
{
    public static function getResourceFullName(array $resource): string
    {
        return ProcessorItemUtils::getItemFullName($resource);
    }
    public static function getResourceFromFullName(string $resourceFullName): ?array
    {
        return ProcessorItemUtils::getItemFromFullName($resourceFullName);
    }
    public static function getResourceOutputName(array $resource): string
    {
        return ProcessorItemUtils::getItemOutputName($resource, DefinitionGroups::RESOURCES);
    }
    public static function getResourceFromOutputName(string $resourceOutputName): ?array
    {
        return ProcessorItemUtils::getItemFromOutputName($resourceOutputName, DefinitionGroups::RESOURCES);
    }
}
