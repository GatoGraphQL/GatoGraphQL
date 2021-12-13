<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\TypeResolvers\ScalarType;

use PoP\ComponentModel\TypeResolvers\ScalarType\AbstractScalarTypeResolver;
use stdClass;

/**
 * This type is similar to Enum in that only values from a fixed-set
 * can be provided, but it is validated as a String.
 *
 * This is because Enum values have several constraints:
 *
 * - Can't have spaces
 * - Can't have special chars, such as `-`
 *
 * For whenever the option values may not satisfy these constraints,
 * this type can be used instead
 */
abstract class AbstractSelectableStringScalarTypeResolver extends AbstractScalarTypeResolver
{
    /** @var string[]|null */
    protected ?array $consolidatedPossibleValuesCache = null;

    public const HOOK_POSSIBLE_VALUES = __CLASS__ . ':possible-values';

    public function coerceValue(string|int|float|bool|stdClass $inputValue): string|int|float|bool|object
    {
        if ($error = $this->validateIsNotStdClass($inputValue)) {
            return $error;
        }

        $possibleValues = $this->getConsolidatedPossibleValues();
        if (!in_array($inputValue, $possibleValues)) {
            return $this->getError(
                sprintf(
                    $this->getTranslationAPI()->__('Value \'%1$s\' for type \'%2$s\' is not valid (the only valid values are: \'%3$s\')', 'component-model'),
                    $inputValue,
                    $this->getMaybeNamespacedTypeName(),
                    implode($this->getTranslationAPI()->__('\', \''), $possibleValues)
                )
            );
        }

        return (string) $inputValue;
    }

    /**
     * Consolidation of the schema directive arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     *
     * @return string[]
     */
    final public function getConsolidatedPossibleValues(): array
    {
        if ($this->consolidatedPossibleValuesCache !== null) {
            return $this->consolidatedPossibleValuesCache;
        }

        /**
         * Allow to override/extend the enum values
         */
        $this->consolidatedPossibleValuesCache = $this->getHooksAPI()->applyFilters(
            self::HOOK_POSSIBLE_VALUES,
            $this->getPossibleValues(),
            $this,
        );
        return $this->consolidatedPossibleValuesCache;
    }

    /**
     * @return string[]
     */
    abstract public function getPossibleValues(): array;
}
