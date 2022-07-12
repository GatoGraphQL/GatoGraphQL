<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\TypeResolvers\ScalarType;

use DateTime;
use DateTimeInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\TypeResolvers\ScalarType\AbstractScalarTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoPSchema\SchemaCommons\FeedbackItemProviders\InputValueCoercionErrorFeedbackItemProvider;
use stdClass;

/**
 * GraphQL Custom Scalar
 *
 * @see https://spec.graphql.org/draft/#sec-Scalars.Custom-Scalars
 */
abstract class AbstractDateTimeScalarTypeResolver extends AbstractScalarTypeResolver
{
    public function getTypeDescription(): ?string
    {
        return sprintf(
            $this->__('%s scalar. It follows the ISO 8601 specification, with format "%s")', 'schema-commons'),
            $this->getTypeName(),
            $this->getDateTimeFormat()
        );
    }

    public function coerceValue(
        string|int|float|bool|stdClass $inputValue,
        AstInterface $astNode,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int|float|bool|object|null {
        $separateObjectTypeFieldResolutionFeedbackStore = new ObjectTypeFieldResolutionFeedbackStore();
        $this->validateIsString($inputValue, $astNode, $separateObjectTypeFieldResolutionFeedbackStore);
        $objectTypeFieldResolutionFeedbackStore->incorporate($separateObjectTypeFieldResolutionFeedbackStore);
        if ($separateObjectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
            return null;
        }

        /**
         * Validate the input has any of the supported formats
         *
         * @see https://stackoverflow.com/a/13194398
         */
        foreach ($this->getDateTimeInputFormats() as $format) {
            $dt = DateTime::createFromFormat($format, $inputValue);
            if ($dt === false || array_sum($dt::getLastErrors())) {
                continue;
            }
            return $dt;
        }

        $objectTypeFieldResolutionFeedbackStore->addError(
            new ObjectTypeFieldResolutionFeedback(
                new FeedbackItemResolution(
                    InputValueCoercionErrorFeedbackItemProvider::class,
                    InputValueCoercionErrorFeedbackItemProvider::E1,
                    [
                        $this->getMaybeNamespacedTypeName(),
                        $this->getDateTimeFormat(),
                    ]
                ),
                LocationHelper::getNonSpecificLocation(),
                $this
            ),
        );
        return null;
    }

    abstract protected function getDateTimeFormat(): string;

    /**
     * Allow to define more than one input format, so that
     * Date can be represented as either:
     *
     *   - 'Y-m-d'
     *   - 'Y-m-d\TH:i:sP'
     *
     * This is needed for the DateTimeObjectSerializer,
     * which is unable to tell if the input is Date or DateTime,
     * so using the 'Y-m-d\TH:i:sP' format can support both cases.
     *
     * @return string[]
     */
    protected function getDateTimeInputFormats(): array
    {
        return [
            $this->getDateTimeFormat(),
        ];
    }

    /**
     * Because DateTimeObjectSerializer also uses the same format 'Y-m-d\TH:i:sP',
     * override this function to provide the specific format for each case
     */
    public function serialize(string|int|float|bool|object $scalarValue): string|int|float|bool|array
    {
        /** @var DateTimeInterface $scalarValue */
        return $scalarValue->format($this->getDateTimeFormat());
    }
}
