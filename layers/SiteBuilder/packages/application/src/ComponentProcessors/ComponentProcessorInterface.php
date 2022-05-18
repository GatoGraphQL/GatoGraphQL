<?php

declare(strict_types=1);

namespace PoP\Application\ComponentProcessors;

use PoP\ConfigurationComponentModel\ComponentProcessors\ComponentProcessorInterface as UpstreamComponentProcessorInterface;

interface ComponentProcessorInterface extends UpstreamComponentProcessorInterface
{
    public function getDataloadMultidomainSources(array $componentVariation, array &$props): array;
    public function getDataloadMultidomainQuerySources(array $componentVariation, array &$props): array;
    public function queriesExternalDomain(array $componentVariation, array &$props): bool;
    public function isMultidomain(array $componentVariation, array &$props): bool;
    public function isLazyload(array $componentVariation, array &$props): bool;
}
