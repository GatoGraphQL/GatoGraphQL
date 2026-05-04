<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Engine;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use SplObjectStorage;

class EngineState
{
    /**
     * @param array<string,mixed> $data
     * @param array<string,mixed> $helperCalculations
     * @param array<string,mixed> $model_props
     * @param array<string,mixed> $props
     * @param string[] $nocache_fields
     * @param array<string,mixed>|null $componentdata
     * @param array<string,array<string,mixed>> $dbdata
     * @param array<string,string[]> $backgroundload_urls
     * @param string[]|null $extra_routes
     * @param array<string,mixed> $outputData
     * @param array<string,array<string,RelationalTypeResolverInterface|array<string|int,EngineIterationFieldSet>>> $relationalTypeOutputKeyIDFieldSets `mixed` could be string[] for "direct", or array<string,string[]> for "conditional"
     * @param array<string,array<string|int,FieldInterface[]>> $already_loaded_id_fields Map of typeOutputKey => ID => already-loaded fields. Persists across drains of the relational queue so subsequent drains do not re-fetch fields already loaded.
     * @param array<string,array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>>> $databases Drain accumulator: dbName => typeOutputKey => id => SplObjectStorage(FieldInterface => value). Lives on EngineState so multiple `generateDatabases()` drains within one pass append into the same store. (Without this, per-pass `array_replace_recursive` of `engineState->data` would replace the leaf SplObjectStorage instead of merging fields.)
     * @param array<string,array<string,array<string|int,SplObjectStorage<FieldInterface,array<string|int>>>>> $unionTypeOutputKeyIDs Drain accumulator for union-typed entries.
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,array<string|int>>>> $combinedUnionTypeOutputKeyIDs Drain accumulator (combined view) for union-typed entries.
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>> $previouslyResolvedIDFieldValues Read-only view of resolved values, exposed to directives.
     * @param array<string,mixed> $messages Per-request inter-directive message bag, accumulated across drains.
     * @param array<string,array<string,array<string,SplObjectStorage<FieldInterface,array<string,mixed>>>>> $schemaFeedbackEntries Drain accumulator for schema-level feedback (errors/warnings/etc.) keyed by `FeedbackCategory => dbName => typeOutputKey => SplObjectStorage(FieldInterface => entry)`. Lives on EngineState because the leaf SplObjectStorage would otherwise be replaced (not merged) by `array_replace_recursive` between successive `processAndGenerateData()` calls under "Sequential Pass" MQE — meaning earlier ops' feedback would be clobbered by later ops writing empty stores at the same path.
     * @param array<string,array<string,array<string,SplObjectStorage<FieldInterface,array<string,mixed>>>>> $objectFeedbackEntries Same shape and reasoning as `$schemaFeedbackEntries`, but for object-level feedback.
     * @param bool $propsHaveBeenComputed `false` until `model_props`/`props`/`componentDatasetSettings` have been computed at least once in this engine state's lifetime. Together with `$propsComputedForRoute` it determines whether the cached values are still valid for the current request iteration. A separate flag is used (rather than relying on `null` as a sentinel) because `null` is also a valid value for `'route'` app state.
     * @param string|null $propsComputedForRoute Tracks the route value for which `$model_props`/`$props`/`$componentDatasetSettings` were last computed. Used by `processAndGenerateData()` to skip re-walking the (union) component tree on every per-operation MQE drain — under SEQUENTIAL_PASS the route doesn't change across the 100+ ops in one generateData(), so these are stable. Invalidates if the route changes (eg: across the extra-routes loop).
     * @param array<string,mixed> $componentDatasetSettings Cached return value of `Engine::getComponentDatasetSettings()` for the current route (ditto). The computed tree-walk is identical for every per-op call, so it lives on EngineState and is reused.
     */
    public function __construct(
        public array $data = [],
        public array $helperCalculations = [],
        public array $model_props = [],
        public array $props = [],
        public array $nocache_fields = [],
        public ?array $componentdata = null,
        public array $dbdata = [],
        public array $backgroundload_urls = [],
        public ?array $extra_routes = null,
        public ?bool $cachedsettings = null,
        public array $outputData = [],
        public ?Component $entryComponent = null,
        public array $relationalTypeOutputKeyIDFieldSets = [],
        public array $already_loaded_id_fields = [],
        public array $databases = [],
        public array $unionTypeOutputKeyIDs = [],
        public array $combinedUnionTypeOutputKeyIDs = [],
        public array $previouslyResolvedIDFieldValues = [],
        public array $messages = [],
        public array $schemaFeedbackEntries = [],
        public array $objectFeedbackEntries = [],
        public bool $propsHaveBeenComputed = false,
        public ?string $propsComputedForRoute = null,
        public array $componentDatasetSettings = [],
    ) {
    }
}
