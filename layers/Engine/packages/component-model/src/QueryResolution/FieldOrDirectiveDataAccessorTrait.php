<?php

declare(strict_types=1);

namespace PoP\ComponentModel\QueryResolution;

use PoP\ComponentModel\App;
use PoP\GraphQLParser\Exception\AbstractValueResolutionPromiseException;
use PoP\GraphQLParser\ExtendedSpec\Execution\ValueResolutionPromiseInterface;
use PoP\GraphQLParser\Module;
use PoP\GraphQLParser\ModuleConfiguration;
use stdClass;

trait FieldOrDirectiveDataAccessorTrait
{
    /**
     * Resolve all the ValueResolutionPromiseInterface to their resolved values.
     *
     * @return array<string,mixed>
     * @throws AbstractValueResolutionPromiseException
     */
    private function doGetResolvedFieldOrDirectiveArgs(array $fieldOrDirectiveArgs): array
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if (
            !($moduleConfiguration->enableDynamicVariables()
            || $moduleConfiguration->enableObjectResolvedFieldValueReferences()
            )
        ) {
            return $fieldOrDirectiveArgs;
        }

        $resolvedFieldOrDirectiveArgs = [];
        foreach ($fieldOrDirectiveArgs as $key => $value) {
            if ($value instanceof ValueResolutionPromiseInterface) {
                /** @var ValueResolutionPromiseInterface */
                $valueResolutionPromise = $value;
                $resolvedFieldOrDirectiveArgs[$key] = $valueResolutionPromise->resolveValue();
                continue;
            }

            /**
             * An ObjectResolvedFieldValueReference could be provided in a List input:
             *
             *   ```
             *   {
             *     id
             *     echo(value: [$__id])
             *   }
             *   ```
             */
            if (is_array($value)) {
                $resolvedFieldOrDirectiveArgs[$key] = $this->doGetResolvedFieldOrDirectiveArgs($value);
                continue;
            }

            /**
             * An ObjectResolvedFieldValueReference could be provided in an InputObject:
             *
             *   ```
             *   {
             *     id
             *     echo(value: {id: $__id})
             *   }
             *   ```
             */
            if ($value instanceof stdClass) {
                $resolvedFieldOrDirectiveArgs[$key] = (object) $this->doGetResolvedFieldOrDirectiveArgs((array) $value);
                continue;
            }

            $resolvedFieldOrDirectiveArgs[$key] = $value;
        }
        return $resolvedFieldOrDirectiveArgs;
    }

    /**
     * @return string[]
     * @throws AbstractValueResolutionPromiseException
     */
    public function getProperties(): array
    {
        return array_keys($this->getResolvedFieldOrDirectiveArgs());
    }
    
    /**
     * @return array<string,mixed>
     * @throws AbstractValueResolutionPromiseException
     */
    abstract protected function getResolvedFieldOrDirectiveArgs(): array;

    /**
     * @throws AbstractValueResolutionPromiseException
     */
    public function hasValue(string $propertyName): bool
    {
        return array_key_exists($propertyName, $this->getResolvedFieldOrDirectiveArgs());
    }

    /**
     * @throws AbstractValueResolutionPromiseException
     */
    public function getValue(string $propertyName): mixed
    {
        return $this->getResolvedFieldOrDirectiveArgs()[$propertyName] ?? null;
    }
}
