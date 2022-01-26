<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Engine;

use PoP\ComponentModel\Error\Error;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

interface EngineInterface
{
    public function getOutputData(): array;
    public function addBackgroundUrl(string $url, array $targets): void;
    public function getEntryModule(): array;
    public function getExtraRoutes(): array;
    public function listExtraRouteVars(): array;
    public function generateDataAndPrepareResponse(): void;
    public function calculateOutputData(): void;
    public function getModelPropsModuletree(array $module): array;
    public function addRequestPropsModuletree(array $module, array $props): array;
    public function getModuleDatasetSettings(array $module, $model_props, array &$props): array;
    public function getRequestMeta(): array;
    public function getSessionMeta(): array;
    public function getSiteMeta(): array;
    public function validateCheckpoints(array $checkpoints): bool | Error;
    public function getModuleData(array $root_module, array $root_model_props, array $root_props): array;
    public function moveEntriesUnderDBName(array $entries, bool $entryHasId, RelationalTypeResolverInterface $relationalTypeResolver): array;
    public function getDatabases(): array;
}
