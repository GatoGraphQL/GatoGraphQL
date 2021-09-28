<?php

declare(strict_types=1);

namespace PoP\Application\ModuleProcessors;

use PoP\ConfigurationComponentModel\ModuleProcessors\ModuleProcessorInterface as UpstreamModuleProcessorInterface;

interface ModuleProcessorInterface extends UpstreamModuleProcessorInterface
{
    public function getDataloadMultidomainSources(array $module, array &$props): array;
    public function getDataloadMultidomainQuerySources(array $module, array &$props): array;
    public function queriesExternalDomain(array $module, array &$props): bool;
    public function isMultidomain(array $module, array &$props): bool;
    public function isLazyload(array $module, array &$props): bool;
}
