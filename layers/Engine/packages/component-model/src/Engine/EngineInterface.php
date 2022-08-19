<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Engine;

use PoP\ComponentModel\Checkpoints\CheckpointInterface;
use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\Feedback\FeedbackItemResolution;
use SplObjectStorage;

interface EngineInterface
{
    /**
     * @return array<string,mixed>
     */
    public function getOutputData(): array;
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
     */
    public function addRequestPropsComponentTree(Component $component, array $props): array;
    /**
     * @return array<string,mixed>
     */
    public function getComponentDatasetSettings(Component $component, $model_props, array &$props): array;
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
     */
    public function getComponentData(Component $root_component, array $root_model_props, array $root_props): array;
    /**
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $entries
     * @return array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>>
     */
    public function moveEntriesWithIDUnderDBName(array $entries, RelationalTypeResolverInterface $relationalTypeResolver): array;
}
