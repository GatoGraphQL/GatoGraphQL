<?php

declare(strict_types=1);

namespace PoP\ConfigurationComponentModel\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\ComponentProcessors\ComponentProcessorInterface as UpstreamComponentProcessorInterface;

interface ComponentProcessorInterface extends UpstreamComponentProcessorInterface
{
    public function getImmutableSettingsComponentTree(Component $component, array &$props): array;
    public function getImmutableSettings(Component $component, array &$props): array;
    public function getImmutableConfiguration(Component $component, array &$props): array;
    public function getMutableonmodelSettingsComponentTree(Component $component, array &$props): array;
    public function getMutableonmodelSettings(Component $component, array &$props): array;
    public function getMutableonmodelConfiguration(Component $component, array &$props): array;
    public function getMutableonrequestSettingsComponentTree(Component $component, array &$props): array;
    public function getMutableonrequestSettings(Component $component, array &$props): array;
    public function getMutableonrequestConfiguration(Component $component, array &$props): array;
    public function getRelevantRoute(Component $component, array &$props): ?string;
    public function getRelevantRouteCheckpointTarget(Component $component, array &$props): string;
    public function getDataloadSource(Component $component, array &$props): ?string;
}
