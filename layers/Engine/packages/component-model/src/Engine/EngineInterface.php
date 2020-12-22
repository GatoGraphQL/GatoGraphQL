<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Engine;

interface EngineInterface
{
    public function getOutputData();
    public function addBackgroundUrl($url, $targets);
    public function getEntryModule(): array;
    public function sendEtagHeader();
    public function getExtraRoutes(): array;
    public function listExtraRouteVars();
    public function generateData();
    public function calculateOutuputData();
    public function getModelPropsModuletree(array $module);
    public function addRequestPropsModuletree(array $module, array $props);
    public function getModuleDatasetSettings(array $module, $model_props, array &$props);
    public function getRequestMeta();
    public function getSessionMeta();
    public function getSiteMeta();
    public function validateCheckpoints($checkpoints);
    public function getModuleData($root_module, $root_model_props, $root_props);
    public function moveEntriesUnderDBName(array $entries, bool $entryHasId, $typeResolver): array;
    public function getDatabases();
}
