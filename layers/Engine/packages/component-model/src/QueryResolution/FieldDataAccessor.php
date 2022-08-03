<?php

declare(strict_types=1);

namespace PoP\ComponentModel\QueryResolution;

use PoP\ComponentModel\App;
use PoP\GraphQLParser\Exception\Parser\DeferredValuePromiseExceptionInterface;
use PoP\GraphQLParser\ExtendedSpec\Execution\DeferredValuePromiseInterface;
use PoP\GraphQLParser\Module;
use PoP\GraphQLParser\ModuleConfiguration;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use stdClass;

class FieldDataAccessor implements FieldDataAccessorInterface
{
    /**
     * A ObjectResolvedFieldValueReference will return a DeferredValuePromiseInterface,
     * which must be resolved to the actual value after its corresponding
     * Field was resolved.
     *
     * @var array<string,mixed>
     */
    protected ?array $resolvedFieldArgs = null;

    public function __construct(
        protected FieldInterface $field,
        /** @var array<string,mixed> */
        protected array $fieldArgs,
    ) {
    }

    public function getField(): FieldInterface
    {
        return $this->field;
    }

    final public function getFieldName(): string
    {
        return $this->field->getName();
    }

    /**
     * @return string[]
     */
    public function getProperties(): array
    {
        return array_keys($this->getKeyValuesSource());
    }

    /**
     * @return array<string,mixed>
     * @throws DeferredValuePromiseExceptionInterface
     */
    protected function getKeyValuesSource(): array
    {
        return $this->getResolvedFieldArgs();
    }

    /**
     * @return array<string,mixed>
     * @throws DeferredValuePromiseExceptionInterface
     */
    protected function getResolvedFieldArgs(): array
    {
        if ($this->resolvedFieldArgs === null) {
            $this->resolvedFieldArgs = $this->resolveFieldArgs($this->fieldArgs);
        }
        return $this->resolvedFieldArgs;
    }

    /**
     * Resolve all the DeferredValuePromiseInterface to their resolved values.
     *
     * @return array<string,mixed>
     * @throws DeferredValuePromiseExceptionInterface
     */
    protected function resolveFieldArgs(array $fieldArgs): array
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if (!$moduleConfiguration->enableObjectResolvedFieldValueReferences()) {
            return $fieldArgs;
        }

        $resolvedFieldArgs = [];
        foreach ($fieldArgs as $key => $value) {
            if ($value instanceof DeferredValuePromiseInterface) {
                /** @var DeferredValuePromiseInterface */
                $deferredValuePromise = $value;
                $resolvedFieldArgs[$key] = $deferredValuePromise->resolveValue();
                continue;
            }

            /**
             * An ObjectResolvedFieldValueReference could be provided in a List input:
             *
             *   ```
             *   {
             *     id
             *     echo(value: [$_id])
             *   }
             *   ```
             */
            if (is_array($value)) {
                $resolvedFieldArgs[$key] = $this->resolveFieldArgs($value);
                continue;
            }

            /**
             * An ObjectResolvedFieldValueReference could be provided in an InputObject:
             *
             *   ```
             *   {
             *     id
             *     echo(value: {id: $_id})
             *   }
             *   ```
             */
            if ($value instanceof stdClass) {
                $resolvedFieldArgs[$key] = (object) $this->resolveFieldArgs((array) $value);
                continue;
            }

            $resolvedFieldArgs[$key] = $value;
        }
        return $resolvedFieldArgs;
    }

    /**
     * @return array<string,mixed>
     * @throws DeferredValuePromiseExceptionInterface
     */
    public function getKeyValues(): array
    {
        return $this->getKeyValuesSource();
    }

    public function hasValue(string $propertyName): bool
    {
        return array_key_exists($propertyName, $this->getKeyValuesSource());
    }

    public function getValue(string $propertyName): mixed
    {
        return $this->getKeyValuesSource()[$propertyName] ?? null;
    }
}
