<?php

declare(strict_types=1);

namespace PoP\ConfigurationComponentModel\ComponentProcessors;

use PoP\ComponentModel\ComponentProcessors\ComponentProcessorInterface as UpstreamComponentProcessorInterface;

interface ComponentProcessorInterface extends UpstreamComponentProcessorInterface
{
    public function getImmutableSettingsModuletree(array $componentVariation, array &$props): array;
    public function getImmutableSettings(array $componentVariation, array &$props): array;
    public function getImmutableConfiguration(array $componentVariation, array &$props): array;
    public function getMutableonmodelSettingsModuletree(array $componentVariation, array &$props): array;
    public function getMutableonmodelSettings(array $componentVariation, array &$props): array;
    public function getMutableonmodelConfiguration(array $componentVariation, array &$props): array;
    public function getMutableonrequestSettingsModuletree(array $componentVariation, array &$props): array;
    public function getMutableonrequestSettings(array $componentVariation, array &$props): array;
    public function getMutableonrequestConfiguration(array $componentVariation, array &$props): array;
    public function getRelevantRoute(array $componentVariation, array &$props): ?string;
    public function getRelevantRouteCheckpointTarget(array $componentVariation, array &$props): string;
    public function getDataloadSource(array $componentVariation, array &$props): ?string;
}
