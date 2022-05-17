<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\EnumType;

use PoP\ComponentModel\Module;
use PoP\ComponentModel\ModuleConfiguration;
use PoP\ComponentModel\Feedback\SchemaInputValidationFeedback;
use PoP\ComponentModel\Feedback\SchemaInputValidationFeedbackStore;
use PoP\ComponentModel\FeedbackItemProviders\InputValueCoercionErrorFeedbackItemProvider;
use PoP\ComponentModel\Response\OutputServiceInterface;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use PoP\Root\App;
use PoP\Root\Feedback\FeedbackItemResolution;
use stdClass;

abstract class AbstractEnumTypeResolver extends AbstractTypeResolver implements EnumTypeResolverInterface
{
    /** @var string[]|null */
    protected ?array $consolidatedEnumValuesCache = null;
    /** @var string[]|null */
    protected ?array $consolidatedAdminEnumValuesCache = null;
    /** @var array<string, string|null> */
    protected array $consolidatedEnumValueDescriptionCache = [];
    /** @var array<string, string|null> */
    protected array $consolidatedEnumValueDeprecationMessageCache = [];
    /** @var array<string, array<string,mixed>> */
    protected array $consolidatedEnumValueExtensionsCache = [];
    /** @var array<string,array<string,mixed>>|null */
    protected ?array $schemaDefinitionForEnumCache = null;
    /** @var array<string, array<string,mixed>> */
    protected array $schemaDefinitionForEnumValueCache = [];

    private ?OutputServiceInterface $outputService = null;

    final public function setOutputService(OutputServiceInterface $outputService): void
    {
        $this->outputService = $outputService;
    }
    final protected function getOutputService(): OutputServiceInterface
    {
        return $this->outputService ??= $this->instanceManager->getInstance(OutputServiceInterface::class);
    }

    /**
     * The "admin" values in the enum
     *
     * @return string[]
     */
    public function getAdminEnumValues(): array
    {
        return [];
    }

    /**
     * By default, no description
     */
    public function getEnumValueDescription(string $enumValue): ?string
    {
        return null;
    }

    /**
     * Deprecation message for a specific enum value
     */
    public function getEnumValueDeprecationMessage(string $enumValue): ?string
    {
        return null;
    }

    /**
     * The validation that the enum value is valid is done in
     * `doValidateEnumFieldOrDirectiveArgumentsItem`.
     *
     * This function simply returns the same value always.
     */
    public function coerceValue(
        string|int|float|bool|stdClass $inputValue,
        SchemaInputValidationFeedbackStore $schemaInputValidationFeedbackStore,
    ): string|int|float|bool|object|null {
        $separateSchemaInputValidationFeedbackStore = new SchemaInputValidationFeedbackStore();
        $this->validateIsString($inputValue, $separateSchemaInputValidationFeedbackStore);
        $schemaInputValidationFeedbackStore->incorporate($separateSchemaInputValidationFeedbackStore);
        if ($separateSchemaInputValidationFeedbackStore->getErrors() !== []) {
            return null;
        }

        $enumValues = $this->getConsolidatedEnumValues();
        if (!in_array($inputValue, $enumValues)) {
            $nonDeprecatedEnumValues = array_filter(
                $enumValues,
                fn (string $enumValue) => empty($this->getConsolidatedEnumValueDeprecationMessage($enumValue))
            );
            $schemaInputValidationFeedbackStore->addError(
                new SchemaInputValidationFeedback(
                    new FeedbackItemResolution(
                        InputValueCoercionErrorFeedbackItemProvider::class,
                        InputValueCoercionErrorFeedbackItemProvider::E14,
                        [
                            $inputValue,
                            $this->getMaybeNamespacedTypeName(),
                            implode($this->__('\', \''), $nonDeprecatedEnumValues)
                        ]
                    ),
                    LocationHelper::getNonSpecificLocation(),
                    $this
                ),
            );
            return null;
        }
        return $inputValue;
    }

    final protected function validateIsString(
        string|int|float|bool|stdClass $inputValue,
        SchemaInputValidationFeedbackStore $schemaInputValidationFeedbackStore,
    ): void {
        if (is_string($inputValue)) {
            return;
        }
        $inputValueAsString = $inputValue instanceof stdClass
            ? $this->getOutputService()->jsonEncodeArrayOrStdClassValue($inputValue)
            : (string) $inputValue;
        $schemaInputValidationFeedbackStore->addError(
            new SchemaInputValidationFeedback(
                new FeedbackItemResolution(
                    InputValueCoercionErrorFeedbackItemProvider::class,
                    InputValueCoercionErrorFeedbackItemProvider::E18,
                    [
                        $inputValueAsString,
                        $this->getMaybeNamespacedTypeName(),
                    ]
                ),
                LocationHelper::getNonSpecificLocation(),
                $this
            ),
        );
    }

    /**
     * Return as is
     */
    public function serialize(string|int|float|bool|object $scalarValue): string|int|float|bool|array
    {
        return $scalarValue;
    }

    /**
     * Obtain the deprecation messages for an input value.
     *
     * @param string|int|float|bool|stdClass $inputValue the (custom) scalar in any format: itself (eg: an object) or its representation (eg: as a string)
     * @return string[] The deprecation messages
     */
    final public function getInputValueDeprecationMessages(string|int|float|bool|stdClass $inputValue): array
    {
        if ($deprecationMessage = $this->getConsolidatedEnumValueDeprecationMessage($inputValue)) {
            return [
                sprintf(
                    $this->__('Enum value \'%s\' is deprecated: %s', 'component-model'),
                    $inputValue,
                    $deprecationMessage
                ),
            ];
        }
        return [];
    }

    /**
     * Consolidation of the schema directive arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     *
     * @return string[]
     */
    final public function getConsolidatedEnumValues(): array
    {
        if ($this->consolidatedEnumValuesCache !== null) {
            return $this->consolidatedEnumValuesCache;
        }

        /**
         * Allow to override/extend the enum values
         */
        $consolidatedEnumValues = App::applyFilters(
            HookNames::ENUM_VALUES,
            $this->getEnumValues(),
            $this,
        );

        // Exclude the admin enum values, if "Admin" Schema is not enabled
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if (!$moduleConfiguration->enableAdminSchema()) {
            $adminEnumValues = $this->getConsolidatedAdminEnumValues();
            $consolidatedEnumValues = array_filter(
                $consolidatedEnumValues,
                fn (string $enumValue) => !in_array($enumValue, $adminEnumValues)
            );
        }

        $this->consolidatedEnumValuesCache = $consolidatedEnumValues;
        return $this->consolidatedEnumValuesCache;
    }

    final public function getConsolidatedAdminEnumValues(): array
    {
        // Cache the result
        if ($this->consolidatedAdminEnumValuesCache !== null) {
            return $this->consolidatedAdminEnumValuesCache;
        }
        return $this->consolidatedAdminEnumValuesCache = App::applyFilters(
            HookNames::ADMIN_ENUM_VALUES,
            $this->getAdminEnumValues(),
            $this,
        );
    }

    final public function getConsolidatedEnumValueDescription(string $enumValue): ?string
    {
        // Cache the result
        if (array_key_exists($enumValue, $this->consolidatedEnumValueDescriptionCache)) {
            return $this->consolidatedEnumValueDescriptionCache[$enumValue];
        }
        return $this->consolidatedEnumValueDescriptionCache[$enumValue] = App::applyFilters(
            HookNames::ENUM_VALUE_DESCRIPTION,
            $this->getEnumValueDescription($enumValue),
            $this,
            $enumValue,
        );
    }

    final public function getConsolidatedEnumValueDeprecationMessage(string $enumValue): ?string
    {
        // Cache the result
        if (array_key_exists($enumValue, $this->consolidatedEnumValueDeprecationMessageCache)) {
            return $this->consolidatedEnumValueDeprecationMessageCache[$enumValue];
        }
        return $this->consolidatedEnumValueDeprecationMessageCache[$enumValue] = App::applyFilters(
            HookNames::ENUM_VALUE_DEPRECATION_MESSAGE,
            $this->getEnumValueDeprecationMessage($enumValue),
            $this,
            $enumValue,
        );
    }


    /**
     * Get the "schema" properties as for the enum
     */
    final public function getEnumSchemaDefinition(): array
    {
        // Cache the result
        if ($this->schemaDefinitionForEnumCache !== null) {
            return $this->schemaDefinitionForEnumCache;
        }

        $enumSchemaDefinition = [];
        $enumValues = $this->getConsolidatedEnumValues();
        foreach ($enumValues as $enumValue) {
            $enumSchemaDefinition[$enumValue] = $this->getEnumValueSchemaDefinition($enumValue);
        }
        $this->schemaDefinitionForEnumCache = $enumSchemaDefinition;
        return $this->schemaDefinitionForEnumCache;
    }

    /**
     * Get the "schema" properties as for the enumValue
     */
    final public function getEnumValueSchemaDefinition(string $enumValue): array
    {
        // Cache the result
        if (isset($this->schemaDefinitionForEnumValueCache[$enumValue])) {
            return $this->schemaDefinitionForEnumValueCache[$enumValue];
        }

        $enumValueSchemaDefinition = [
            SchemaDefinition::VALUE => $enumValue,
        ];
        if ($description = $this->getConsolidatedEnumValueDescription($enumValue)) {
            $enumValueSchemaDefinition[SchemaDefinition::DESCRIPTION] = $description;
        }
        if ($deprecationMessage = $this->getConsolidatedEnumValueDeprecationMessage($enumValue)) {
            $enumValueSchemaDefinition[SchemaDefinition::DEPRECATED] = true;
            $enumValueSchemaDefinition[SchemaDefinition::DEPRECATION_MESSAGE] = $deprecationMessage;
        }
        $enumValueSchemaDefinition[SchemaDefinition::EXTENSIONS] = $this->getConsolidatedEnumValueExtensionsSchemaDefinition($enumValue);

        $this->schemaDefinitionForEnumValueCache[$enumValue] = $enumValueSchemaDefinition;
        ;
        return $this->schemaDefinitionForEnumValueCache[$enumValue];
    }

    protected function getEnumValueExtensionsSchemaDefinition(string $enumValue): array
    {
        return [
            SchemaDefinition::IS_ADMIN_ELEMENT => in_array($enumValue, $this->getConsolidatedAdminEnumValues()),
        ];
    }

    /**
     * Consolidation of the schema inputs. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     */
    final public function getConsolidatedEnumValueExtensionsSchemaDefinition(string $enumValue): array
    {
        if (array_key_exists($enumValue, $this->consolidatedEnumValueExtensionsCache)) {
            return $this->consolidatedEnumValueExtensionsCache[$enumValue];
        }
        $this->consolidatedEnumValueExtensionsCache[$enumValue] = App::applyFilters(
            HookNames::ENUM_VALUE_EXTENSIONS,
            $this->getEnumValueExtensionsSchemaDefinition($enumValue),
            $this,
            $enumValue,
        );
        return $this->consolidatedEnumValueExtensionsCache[$enumValue];
    }
}
