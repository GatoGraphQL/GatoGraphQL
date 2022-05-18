<?php

declare(strict_types=1);

namespace PoP\Application\ComponentProcessors;

use PoP\ConfigurationComponentModel\ComponentProcessors\ComponentProcessorInterface as UpstreamComponentProcessorInterface;

interface ComponentProcessorInterface extends UpstreamComponentProcessorInterface
{
    public function getDataloadMultidomainSources(array $module, array &$props): array;
    public function getDataloadMultidomainQuerySources(array $module, array &$props): array;
    public function queriesExternalDomain(array $module, array &$props): bool;
    public function isMultidomain(array $module, array &$props): bool;
    public function isLazyload(array $module, array &$props): bool;
}
