<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Location;

class ObjectTypeFieldResolutionFeedback extends AbstractQueryFeedback implements ObjectTypeFieldResolutionFeedbackInterface
{
    public function __construct(
        FeedbackItemResolution $feedbackItemResolution,
        Location $location,
        protected RelationalTypeResolverInterface $relationalTypeResolver,
        /** @var array<string, mixed> */
        array $extensions = [],
        /** @var array<string, mixed> */
        array $data = [],
        /** @var ObjectTypeFieldResolutionFeedbackInterface[] */
        protected array $nested = [],
    ) {
        parent::__construct(
            $feedbackItemResolution,
            $location,
            $extensions,
            $data,
        );
    }

    public static function fromSchemaInputValidationFeedback(
        SchemaInputValidationFeedbackInterface $schemaInputValidationFeedback,
        RelationalTypeResolverInterface $relationalTypeResolver,
    ): self {
        /** @var ObjectTypeFieldResolutionFeedbackInterface[] */
        $nestedObjectTypeFieldResolutionFeedbackEntries = [];
        foreach ($schemaInputValidationFeedback->getNested() as $nestedSchemaInputValidationFeedback) {
            $nestedObjectTypeFieldResolutionFeedbackEntries[] = static::fromSchemaInputValidationFeedback(
                $nestedSchemaInputValidationFeedback,
                $relationalTypeResolver,
            );
        }
        return new self(
            $schemaInputValidationFeedback->getFeedbackItemResolution(),
            $schemaInputValidationFeedback->getLocation(),
            $relationalTypeResolver,
            $schemaInputValidationFeedback->getExtensions(),
            $schemaInputValidationFeedback->getData(),
            $nestedObjectTypeFieldResolutionFeedbackEntries
        );
    }

    /**
     * @return ObjectTypeFieldResolutionFeedbackInterface[]
     */
    public function getNested(): array
    {
        return $this->nested;
    }
}
