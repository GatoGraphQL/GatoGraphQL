<?php

declare(strict_types=1);

namespace PoP\ConfigurationComponentModel\ComponentProcessors;

use PoP\ComponentModel\ComponentProcessors\ComponentProcessorInterface as UpstreamComponentProcessorInterface;

interface ComponentProcessorInterface extends UpstreamComponentProcessorInterface
{
    public function getImmutableSettingsModuletree(array $component, array &$props): array;
    public function getImmutableSettings(array $component, array &$props): array;
    public function getImmutableConfiguration(array $component, array &$props): array;
    public function getMutableonmodelSettingsModuletree(array $component, array &$props): array;
    public function getMutableonmodelSettings(array $component, array &$props): array;
    public function getMutableonmodelConfiguration(array $component, array &$props): array;
    public function getMutableonrequestSettingsModuletree(array $component, array &$props): array;
    public function getMutableonrequestSettings(array $component, array &$props): array;
    public function getMutableonrequestConfiguration(array $component, array &$props): array;
    public function getRelevantRoute(array $component, array &$props): ?string;
    public function getRelevantRouteCheckpointTarget(array $component, array &$props): string;
    public function getDataloadSource(array $component, array &$props): ?string;
}
