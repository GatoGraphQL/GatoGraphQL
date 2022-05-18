<?php

declare(strict_types=1);

namespace PoP\Application\ComponentProcessors;

use PoP\ConfigurationComponentModel\ComponentProcessors\ComponentProcessorInterface as UpstreamComponentProcessorInterface;

interface ComponentProcessorInterface extends UpstreamComponentProcessorInterface
{
    public function getDataloadMultidomainSources(array $component, array &$props): array;
    public function getDataloadMultidomainQuerySources(array $component, array &$props): array;
    public function queriesExternalDomain(array $component, array &$props): bool;
    public function isMultidomain(array $component, array &$props): bool;
    public function isLazyload(array $component, array &$props): bool;
}
