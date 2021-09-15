<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Enums;

use Exception;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface;
use PoP\ComponentModel\Schema\SchemaNamespacingServiceInterface;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;

abstract class AbstractEnumTypeResolver extends AbstractTypeResolver implements EnumTypeResolverInterface
{
    protected ?array $uppercaseValueMappings = null;

    /**
     * Validate that, if the enum provides core values,
     * these have the same number of elements as the values
     */
    public function __construct(
        TranslationAPIInterface $translationAPI,
        HooksAPIInterface $hooksAPI,
        InstanceManagerInterface $instanceManager,
        SchemaNamespacingServiceInterface $schemaNamespacingService,
        SchemaDefinitionServiceInterface $schemaDefinitionService,
    ) {
        parent::__construct(
            $translationAPI,
            $hooksAPI,
            $instanceManager,
            $schemaNamespacingService,
            $schemaDefinitionService,
        );
        if (!is_null($this->getCoreValues()) && count($this->getCoreValues()) != count($this->getValues())) {
            throw new Exception(
                sprintf(
                    $this->translationAPI->__('Enum \'%s\' (in class \'%s\') must return the same number of elements in function `getCoreValues()` as in `getValues()`', 'component-model'),
                    $this->getTypeName(),
                    get_called_class()
                )
            );
        }
    }

    public function outputEnumValueInUppercase(): bool
    {
        return true;
    }

    /**
     * The input may in in UPPERCASE while the enum value in the app
     * is stored in lowercase, then convert from one to the other.
     *
     * @param string $inputEnumValue The input enum value, possibly as UPPERCASE
     * @return string|null The found enum value, or `null` if it doesn't exist
     */
    final public function getEnumValueFromInput(string $inputEnumValue): ?string
    {
        if ($this->outputEnumValueInUppercase()) {
            $uppercaseValueMappings = $this->getUppercaseValueMappings();
            return $uppercaseValueMappings[$inputEnumValue] ?? null;
        }
        if (in_array($inputEnumValue, $this->getValues())) {
            return $inputEnumValue;
        }
        return null;
    }

    protected function getUppercaseValueMappings(): array
    {
        if ($this->uppercaseValueMappings === null) {
            $this->uppercaseValueMappings = array_map(
                'strtoupper',
                $this->getValues()
            );
        }
        return $this->uppercaseValueMappings;
    }

    /**
     * Allow the enum to deal with 2 values: the one exposed on the API,
     * and the real one that needs be provided to the application.
     *
     * To pair them, they must be on the same position in their respective arrays:
     * values => ["ONE", "TWO"] and coreValues => ["one", "two"]
     *
     * If `null`, `getValues` is used
     */
    public function getCoreValues(): ?array
    {
        return null;
    }
    /**
     * Given an enum value, obtain its core value
     */
    final public function getCoreValue(string $enumValue): ?string
    {
        // If no core values defined, then search for it in values
        $values = $this->getValues();
        $coreValues = $this->getCoreValues();
        if (!$coreValues) {
            $coreValues = $values;
        }
        // Get the index for the enum in the values
        $pos = array_search($enumValue, $values);
        if ($pos === false) {
            return null;
        }
        // The core value and the value are at the same position in the array
        return $coreValues[$pos];
    }

    public function getDescriptions(): array
    {
        return [];
    }
}
