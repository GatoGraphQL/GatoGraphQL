<?php

declare(strict_types=1);

namespace PoP\ConfigurationComponentModel\ComponentProcessors;

use PoP\ComponentModel\ComponentProcessors\ComponentProcessorInterface as UpstreamComponentProcessorInterface;

interface ComponentProcessorInterface extends UpstreamComponentProcessorInterface
{
    public function getImmutableSettingsComponentTree(\PoP\ComponentModel\Component\Component $component, array &$props): array;
    public function getImmutableSettings(\PoP\ComponentModel\Component\Component $component, array &$props): array;
    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array;
    public function getMutableonmodelSettingsComponentTree(\PoP\ComponentModel\Component\Component $component, array &$props): array;
    public function getMutableonmodelSettings(\PoP\ComponentModel\Component\Component $component, array &$props): array;
    public function getMutableonmodelConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array;
    public function getMutableonrequestSettingsComponentTree(\PoP\ComponentModel\Component\Component $component, array &$props): array;
    public function getMutableonrequestSettings(\PoP\ComponentModel\Component\Component $component, array &$props): array;
    public function getMutableonrequestConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array;
    public function getRelevantRoute(\PoP\ComponentModel\Component\Component $component, array &$props): ?string;
    public function getRelevantRouteCheckpointTarget(\PoP\ComponentModel\Component\Component $component, array &$props): string;
    public function getDataloadSource(\PoP\ComponentModel\Component\Component $component, array &$props): ?string;
}
