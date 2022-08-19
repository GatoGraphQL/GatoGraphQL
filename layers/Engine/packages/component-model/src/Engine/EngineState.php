<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Engine;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

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
    ) {
    }
}
