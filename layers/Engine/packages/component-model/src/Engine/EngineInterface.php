<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Engine;

use PoP\ComponentModel\Checkpoints\CheckpointInterface;
use PoP\ComponentModel\Component\Component;
use PoP\Root\Feedback\FeedbackItemResolution;

interface EngineInterface
{
    /**
     * @return array<string,mixed>
     */
    public function getOutputData(): array;
    /**
     * @param string[] $targets
     */
    public function addBackgroundUrl(string $url, array $targets): void;
    public function getEntryComponent(): Component;
    /**
     * @return string[]
     */
    public function getExtraRoutes(): array;
    /**
     * @return mixed[]
     */
    public function listExtraRouteVars(): array;
    /** Must call before `generateDataAndPrepareResponse` */
    public function initializeState(): void;
    public function generateDataAndPrepareResponse(): void;
    public function calculateOutputData(): void;
    /**
     * @return array<string,mixed>
     */
    public function getModelPropsComponentTree(Component $component): array;
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     */
    public function addRequestPropsComponentTree(Component $component, array $props): array;
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $model_props
     * @param array<string,mixed> $props
     */
    public function getComponentDatasetSettings(Component $component, array $model_props, array &$props): array;
    /**
     * @return array<string,mixed>
     */
    public function getRequestMeta(): array;
    /**
     * @return array<string,mixed>
     */
    public function getSessionMeta(): array;
    /**
     * @return array<string,mixed>
     */
    public function getSiteMeta(): array;
    /**
     * @param CheckpointInterface[] $checkpoints
     */
    public function validateCheckpoints(array $checkpoints): ?FeedbackItemResolution;
    /**
     * @return mixed[]
     * @param array<string,mixed> $root_model_props
     * @param array<string,mixed> $root_props
     */
    public function getComponentData(Component $root_component, array $root_model_props, array $root_props): array;
}
