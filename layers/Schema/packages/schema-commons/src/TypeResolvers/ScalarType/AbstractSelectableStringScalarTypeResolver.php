<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\TypeResolvers\ScalarType;

use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\SchemaInputValidationFeedback;
use PoP\ComponentModel\Feedback\SchemaInputValidationFeedbackStore;
use PoP\ComponentModel\TypeResolvers\ScalarType\AbstractScalarTypeResolver;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use PoP\Root\App;
use PoPSchema\SchemaCommons\FeedbackItemProviders\InputValueCoercionErrorFeedbackItemProvider;
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

    public final const HOOK_POSSIBLE_VALUES = __CLASS__ . ':possible-values';

    public function coerceValue(
        string|int|float|bool|stdClass $inputValue,
        SchemaInputValidationFeedbackStore $schemaInputValidationFeedbackStore,
    ): string|int|float|bool|object|null {
        $separateSchemaInputValidationFeedbackStore = new SchemaInputValidationFeedbackStore();
        $this->validateIsNotStdClass($inputValue, $separateSchemaInputValidationFeedbackStore);
        $schemaInputValidationFeedbackStore->incorporate($separateSchemaInputValidationFeedbackStore);
        if ($separateSchemaInputValidationFeedbackStore->getErrors() !== []) {
            return null;
        }

        $possibleValues = $this->getConsolidatedPossibleValues();
        if (!in_array($inputValue, $possibleValues)) {
            $schemaInputValidationFeedbackStore->addError(
                new SchemaInputValidationFeedback(
                    new FeedbackItemResolution(
                        InputValueCoercionErrorFeedbackItemProvider::class,
                        InputValueCoercionErrorFeedbackItemProvider::E2,
                        [
                            $inputValue,
                            $this->getMaybeNamespacedTypeName(),
                            implode($this->__('\', \''), $possibleValues),
                        ]
                    ),
                    LocationHelper::getNonSpecificLocation(),
                    $this
                ),
            );
            return null;
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
        $this->consolidatedPossibleValuesCache = App::applyFilters(
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
