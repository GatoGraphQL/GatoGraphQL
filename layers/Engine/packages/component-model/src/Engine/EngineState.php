<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Engine;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

class EngineState
{
    public function __construct(
        /**
         * @var mixed[]
         */
        public array $data = [],
        /**
         * @var mixed[]
         */
        public array $helperCalculations = [],
        /**
         * @var mixed[]
         */
        public array $model_props = [],
        /**
         * @var mixed[]
         */
        public array $props = [],
        /**
         * @var string[]
         */
        public array $nocache_fields = [],
        /**
         * @var array<string,mixed>
         */
        public ?array $componentdata = null,
        /**
         * @var array<string,array>
         */
        public array $dbdata = [],
        /**
         * @var array<string,array>
         */
        public array $backgroundload_urls = [],
        /**
         * @var string[]
         */
        public ?array $extra_routes = null,
        public ?bool $cachedsettings = null,
        /**
         * @var array<string,mixed>
         */
        public array $outputData = [],
        public ?Component $entryComponent = null,
        /**
         * `mixed` could be string[] for "direct", or array<string,string[]> for "conditional"
         * @var array<string,array<string,RelationalTypeResolverInterface|array<string|int,EngineIterationFieldSet>>>
         */
        public array $relationalTypeOutputKeyIDFieldSets = [],
    ) {
    }
}
