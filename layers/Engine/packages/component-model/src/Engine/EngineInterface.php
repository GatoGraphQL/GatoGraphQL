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
    public function getOutputData(): array;
    public function addBackgroundUrl(string $url, array $targets): void;
    public function getEntryComponent(): Component;
    public function getExtraRoutes(): array;
    public function listExtraRouteVars(): array;
    public function generateDataAndPrepareResponse(): void;
    public function calculateOutputData(): void;
    public function getModelPropsComponentTree(Component $component): array;
    public function addRequestPropsComponentTree(Component $component, array $props): array;
    public function getComponentDatasetSettings(Component $component, $model_props, array &$props): array;
    public function getRequestMeta(): array;
    public function getSessionMeta(): array;
    public function getSiteMeta(): array;
    /**
     * @param CheckpointInterface[] $checkpoints
     */
    public function validateCheckpoints(array $checkpoints): ?FeedbackItemResolution;
    public function getComponentData(Component $root_component, array $root_model_props, array $root_props): array;
    /**
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>|null> $entries
     * @return array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>>
     */
    public function moveEntriesWithIDUnderDBName(array $entries, RelationalTypeResolverInterface $relationalTypeResolver): array;
}
