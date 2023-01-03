<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\TypeResolvers\ScalarType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\TypeResolvers\ScalarType\AbstractScalarTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\Root\App;
use PoP\Root\Feedback\FeedbackItemResolution;
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
abstract class AbstractEnumStringScalarTypeResolver extends AbstractScalarTypeResolver implements EnumStringScalarTypeResolverInterface
{
    /** @var string[]|null */
    protected ?array $consolidatedPossibleValuesCache = null;

    public final const HOOK_POSSIBLE_VALUES = __CLASS__ . ':possible-values';

    public function coerceValue(
        string|int|float|bool|stdClass $inputValue,
        AstInterface $astNode,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int|float|bool|object|null {
        $errorCount = $objectTypeFieldResolutionFeedbackStore->getErrorCount();
        $this->validateIsNotStdClass($inputValue, $astNode, $objectTypeFieldResolutionFeedbackStore);
        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return null;
        }
        /** @var string|int|float|bool $inputValue */

        $possibleValues = $this->getConsolidatedPossibleValues();
        if (!in_array($inputValue, $possibleValues)) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        InputValueCoercionErrorFeedbackItemProvider::class,
                        InputValueCoercionErrorFeedbackItemProvider::E2,
                        [
                            $inputValue,
                            $this->getMaybeNamespacedTypeName(),
                            implode($this->__('\', \''), $possibleValues),
                        ]
                    ),
                    $astNode,
                ),
            );
            return null;
        }

        return (string) $inputValue;
    }

    /**
     * Consolidation of the possible values. Call this function to read the data
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

        if ($this->sortPossibleValues()) {
            sort($this->consolidatedPossibleValuesCache);
        }

        return $this->consolidatedPossibleValuesCache;
    }

    public function getTypeDescription(): string
    {
        return sprintf(
            $this->__('Possible values: `"%s"`.', 'schema-commons'),
            implode('"`, `"', $this->getConsolidatedPossibleValues())
        );
    }

    public function sortPossibleValues(): bool
    {
        return true;
    }
}
