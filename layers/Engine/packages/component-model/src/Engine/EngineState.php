<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Engine;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use SplObjectStorage;

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
         * @var array<string, mixed>
         */
        public ?array $componentdata = null,
        /**
         * @var array<string, array>
         */
        public array $dbdata = [],
        /**
         * @var array<string, array>
         */
        public array $backgroundload_urls = [],
        /**
         * @var string[]
         */
        public ?array $extra_routes = null,
        public ?bool $cachedsettings = null,
        /**
         * @var array<string, mixed>
         */
        public array $outputData = [],
        public ?Component $entryComponent = null,
        /**
         * `mixed` could be string[] for "direct", or array<string,string[]> for "conditional"
         * @var array<string,array<string,RelationalTypeResolverInterface|array<string|int,EngineIterationFieldSet>>>
         */
        public array $relationalTypeOutputKeyIDFieldSets = [],
        /**
         * After executing `resolveValue` on the Object/UnionTypeResolver,
         * store the results to re-use for subsequent calls for same object/field.
         *
         * This is mandatory due to the "Object Resolved Field Value References",
         * which re-create the same field in the AST.
         *
         * For instance, in this query, the `id` field is created twice in the AST:
         *
         * ```graphql
         * {
         *   id
         *   echo(value: $_id)
         * }
         * ```
         *
         * But the 2nd AST must not be recalculated.
         *
         * @todo Incorporate with AST to compare against the Field->getLocation(), to make sure 2 fields are indeed the same
         * @todo Check if caching by $feedbackStore is also needed
         * @todo Check if caching by $options is also needed
         * @todo Check how this plays out for mutations; should they be executed more than once? If so, when/how?
         *
         * @var array<string,array<string|id,SplObjectStorage<FieldInterface,mixed>>> Multidimensional array of [$objectTypeResolverNamespacedName][$objectID][$field] => $value
         */
        protected array $objectTypeResolvedValuesCache = [],
    ) {
    }

    public function hasObjectTypeResolvedValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldInterface $field,
    ): bool {
        $objectID = $objectTypeResolver->getID($object);
        return isset($this->objectTypeResolvedValuesCache[$objectTypeResolver->getNamespacedTypeName()][$objectID]) && $this->objectTypeResolvedValuesCache[$objectTypeResolver->getNamespacedTypeName()][$objectID]->contains($field);
    }

    public function getObjectTypeResolvedValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldInterface $field,
    ): mixed {
        $objectID = $objectTypeResolver->getID($object);
        return $this->objectTypeResolvedValuesCache[$objectTypeResolver->getNamespacedTypeName()][$objectID][$field];
    }

    public function setObjectTypeResolvedValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldInterface $field,
        mixed $value,
    ): void {
        $objectID = $objectTypeResolver->getID($object);
        /** @var SplObjectStorage<FieldInterface,mixed> */
        $fieldResolvedValueSplObjectStorage = $this->objectTypeResolvedValuesCache[$objectTypeResolver->getNamespacedTypeName()][$objectID] ?? new SplObjectStorage();
        $fieldResolvedValueSplObjectStorage[$field] = $value;
        $this->objectTypeResolvedValuesCache[$objectTypeResolver->getNamespacedTypeName()][$objectID] = $fieldResolvedValueSplObjectStorage;
    }

    protected function getDataHash(array $data): string
    {
        return json_encode($data);
        // return (string)hash('crc32', json_encode($data));
    }
}
