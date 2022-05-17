<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Engine;

use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

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
        public ?array $moduledata = null,
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
        public ?array $entryComponent = null,
        /**
         * `mixed` could be string[] for "direct", or array<string,string[]> for "conditional"
         * @var array<string,array<string,mixed>>
         */
        public array $relationalTypeOutputDBKeyIDsDataFields = [],
        /**
         * After executing `resolveValue` on the Object/UnionTypeResolver,
         * store the results to re-use for subsequent calls for same object/field.
         *
         * This is mandatory due to the "Resolved Field Variable References",
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
         * @var array<string,array<string,array<string,array<string|id,array<string,mixed>>>>> Multidimensional array of [$objectTypeResolverNamespacedName][$variablesHash][$expressionsHash][$objectID][$field] => $value
         */
        protected array $objectTypeResolvedValuesCache = [],
    ) {
    }

    /**
     * @param array<string, mixed> $variables
     * @param array<string, mixed> $expressions
     */
    public function hasObjectTypeResolvedValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $field,
        array $variables,
        array $expressions,
    ): bool {
        $objectID = $objectTypeResolver->getID($object);
        $variablesHash = $this->getDataHash($variables);
        $expressionsHash = $this->getDataHash($expressions);
        return array_key_exists($field, $this->objectTypeResolvedValuesCache[$objectTypeResolver->getNamespacedTypeName()][$variablesHash][$expressionsHash][$objectID] ?? []);
    }

    /**
     * @param array<string, mixed> $variables
     * @param array<string, mixed> $expressions
     */
    public function getObjectTypeResolvedValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $field,
        array $variables,
        array $expressions,
    ): mixed {
        $objectID = $objectTypeResolver->getID($object);
        $variablesHash = $this->getDataHash($variables);
        $expressionsHash = $this->getDataHash($expressions);
        return $this->objectTypeResolvedValuesCache[$objectTypeResolver->getNamespacedTypeName()][$variablesHash][$expressionsHash][$objectID][$field];
    }

    /**
     * @param array<string, mixed> $variables
     * @param array<string, mixed> $expressions
     */
    public function setObjectTypeResolvedValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $field,
        array $variables,
        array $expressions,
        mixed $value,
    ): void {
        $objectID = $objectTypeResolver->getID($object);
        $variablesHash = $this->getDataHash($variables);
        $expressionsHash = $this->getDataHash($expressions);
        $this->objectTypeResolvedValuesCache[$objectTypeResolver->getNamespacedTypeName()][$variablesHash][$expressionsHash][$objectID][$field] = $value;
    }

    protected function getDataHash(array $data): string
    {
        return json_encode($data);
        // return (string)hash('crc32', json_encode($data));
    }
}
