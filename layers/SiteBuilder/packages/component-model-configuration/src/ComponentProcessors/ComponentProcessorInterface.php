<?php

declare(strict_types=1);

namespace PoP\ConfigurationComponentModel\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\ComponentProcessors\ComponentProcessorInterface as UpstreamComponentProcessorInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use SplObjectStorage;

interface ComponentProcessorInterface extends UpstreamComponentProcessorInterface
{
    /**
     * @return array<string,mixed>
     */
    public function getImmutableSettingsComponentTree(Component $component, array &$props): array;
    /**
     * @return array<string,mixed>
     */
    public function getImmutableSettings(Component $component, array &$props): array;
    /**
     * @return array<string,mixed>
     */
    public function getImmutableConfiguration(Component $component, array &$props): array;
    /**
     * @return array<string,mixed>
     */
    public function getMutableonmodelSettingsComponentTree(Component $component, array &$props): array;
    /**
     * @return array<string,mixed>
     */
    public function getMutableonmodelSettings(Component $component, array &$props): array;
    /**
     * @return array<string,mixed>
     */
    public function getMutableonmodelConfiguration(Component $component, array &$props): array;
    /**
     * @return array<string,mixed>
     */
    public function getMutableonrequestSettingsComponentTree(Component $component, array &$props): array;
    /**
     * @return array<string,mixed>
     */
    public function getMutableonrequestSettings(Component $component, array &$props): array;
    /**
     * @return array<string,mixed>
     */
    public function getMutableonrequestConfiguration(Component $component, array &$props): array;
    public function getRelevantRoute(Component $component, array &$props): ?string;
    public function getRelevantRouteCheckpointTarget(Component $component, array &$props): string;
    public function getDataloadSource(Component $component, array &$props): ?string;
    /**
     * @return SplObjectStorage<FieldInterface,string> Key: field output key, Value: self object or relational type output key
     */
    public function getFieldToTypeOutputKeys(Component $component, array &$props): SplObjectStorage;
}
