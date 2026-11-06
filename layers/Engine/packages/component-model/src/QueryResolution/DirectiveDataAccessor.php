<?php

declare(strict_types=1);

namespace PoP\ComponentModel\QueryResolution;

use BackedEnum;
use PoP\GraphQLParser\Exception\AbstractValueResolutionPromiseException;

use function array_key_exists;
use function array_map;
use function is_array;
use function is_string;

class DirectiveDataAccessor implements DirectiveDataAccessorInterface
{
    use FieldOrDirectiveDataAccessorTrait;

    /**
     * @param array<string,mixed> $unresolvedDirectiveArgs
     * @param array<string,class-string<BackedEnum>> $backedEnumClassesByArgName
     */
    public function __construct(
        /** @var array<string,mixed> */
        protected array $unresolvedDirectiveArgs,
        /** @var array<string,class-string<BackedEnum>> */
        protected array $backedEnumClassesByArgName = [],
    ) {
    }

    /**
     * @return array<string,mixed>
     * @throws AbstractValueResolutionPromiseException
     */
    public function getDirectiveArgs(): array
    {
        $directiveArgs = $this->getResolvedFieldOrDirectiveArgs();
        foreach ($this->backedEnumClassesByArgName as $argName => $backedEnumClass) {
            if (!array_key_exists($argName, $directiveArgs)) {
                continue;
            }
            $directiveArgs[$argName] = $this->coerceResolvedValueToBackedEnum($directiveArgs[$argName], $backedEnumClass);
        }
        return $directiveArgs;
    }

    /**
     * The value resolved from a promise (eg: a dynamic variable) was
     * not coerced together with the other directive args, so the
     * enum value must still be converted into the PHP enum case.
     *
     * @param class-string<BackedEnum> $backedEnumClass
     */
    protected function coerceResolvedValueToBackedEnum(mixed $value, string $backedEnumClass): mixed
    {
        if (is_string($value)) {
            return $backedEnumClass::from($value);
        }
        if (is_array($value)) {
            return array_map(
                fn (mixed $valueItem): mixed => $this->coerceResolvedValueToBackedEnum($valueItem, $backedEnumClass),
                $value
            );
        }
        return $value;
    }

    /**
     * @return array<string,mixed>
     */
    protected function getUnresolvedFieldOrDirectiveArgs(): array
    {
        return $this->unresolvedDirectiveArgs;
    }

    /**
     * When the Args contain a "Resolved on Object" Promise,
     * then caching the results will not work across objects,
     * and the cache must then be explicitly cleared.
     */
    public function resetDirectiveArgs(): void
    {
        $this->resetResolvedFieldOrDirectiveArgs();
    }
}
