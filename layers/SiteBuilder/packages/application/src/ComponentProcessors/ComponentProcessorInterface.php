<?php

declare(strict_types=1);

namespace PoP\Application\ComponentProcessors;

use PoP\ConfigurationComponentModel\ComponentProcessors\ComponentProcessorInterface as UpstreamComponentProcessorInterface;

interface ComponentProcessorInterface extends UpstreamComponentProcessorInterface
{
    public function getDataloadMultidomainSources(\PoP\ComponentModel\Component\Component $component, array &$props): array;
    public function getDataloadMultidomainQuerySources(\PoP\ComponentModel\Component\Component $component, array &$props): array;
    public function queriesExternalDomain(\PoP\ComponentModel\Component\Component $component, array &$props): bool;
    public function isMultidomain(\PoP\ComponentModel\Component\Component $component, array &$props): bool;
    public function isLazyload(\PoP\ComponentModel\Component\Component $component, array &$props): bool;
}
