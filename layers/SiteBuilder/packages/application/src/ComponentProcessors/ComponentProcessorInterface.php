<?php

declare(strict_types=1);

namespace PoP\Application\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\ConfigurationComponentModel\ComponentProcessors\ComponentProcessorInterface as UpstreamComponentProcessorInterface;

interface ComponentProcessorInterface extends UpstreamComponentProcessorInterface
{
    public function getDataloadMultidomainSources(Component $component, array &$props): array;
    /**
     * @return string[]
     */
    public function getDataloadMultidomainQuerySources(Component $component, array &$props): array;
    public function queriesExternalDomain(Component $component, array &$props): bool;
    public function isMultidomain(Component $component, array &$props): bool;
    public function isLazyload(Component $component, array &$props): bool;
}
