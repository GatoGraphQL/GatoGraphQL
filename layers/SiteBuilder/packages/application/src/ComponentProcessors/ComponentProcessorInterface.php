<?php

declare(strict_types=1);

namespace PoP\Application\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\ConfigurationComponentModel\ComponentProcessors\ComponentProcessorInterface as UpstreamComponentProcessorInterface;

interface ComponentProcessorInterface extends UpstreamComponentProcessorInterface
{
    /**
     * @return string[]
     * @param array<string,mixed> $props
     */
    public function getDataloadMultidomainSources(Component $component, array &$props): array;
    /**
     * @return string[]
     * @param array<string,mixed> $props
     */
    public function getDataloadMultidomainQuerySources(Component $component, array &$props): array;
    /**
     * @param array<string,mixed> $props
     */
    public function queriesExternalDomain(Component $component, array &$props): bool;
    /**
     * @param array<string,mixed> $props
     */
    public function isMultidomain(Component $component, array &$props): bool;
    /**
     * @param array<string,mixed> $props
     */
    public function isLazyload(Component $component, array &$props): bool;
}
