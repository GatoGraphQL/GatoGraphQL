<?php

declare(strict_types=1);

namespace PoP\ConfigurationComponentModel\ModuleProcessors;

use PoP\ComponentModel\ModuleProcessors\ModuleProcessorInterface as UpstreamModuleProcessorInterface;

interface ModuleProcessorInterface extends UpstreamModuleProcessorInterface
{
    public function getImmutableSettingsModuletree(array $module, array &$props): array;
    public function getImmutableSettings(array $module, array &$props): array;
    public function getImmutableConfiguration(array $module, array &$props): array;
    public function getMutableonmodelSettingsModuletree(array $module, array &$props): array;
    public function getMutableonmodelSettings(array $module, array &$props): array;
    public function getMutableonmodelConfiguration(array $module, array &$props): array;
    public function getMutableonrequestSettingsModuletree(array $module, array &$props): array;
    public function getMutableonrequestSettings(array $module, array &$props): array;
    public function getMutableonrequestConfiguration(array $module, array &$props): array;
    public function getRelevantRoute(array $module, array &$props): ?string;
    public function getRelevantRouteCheckpointTarget(array $module, array &$props): string;
    public function getDataloadSource(array $module, array &$props): ?string;
}
