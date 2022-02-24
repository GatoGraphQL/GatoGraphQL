<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Location;

class ObjectFeedback extends AbstractQueryFeedback implements ObjectFeedbackInterface
{
    public function __construct(
        FeedbackItemResolution $feedbackItemResolution,
        Location $location,
        protected RelationalTypeResolverInterface $relationalTypeResolver,
        protected string $field,
        protected string|int $objectID,
        protected ?string $directive = null,
        /** @var array<string, mixed> */
        array $extensions = [],
        /** @var ObjectFeedbackInterface[] */
        protected array $nested = [],
    ) {
        parent::__construct(
            $feedbackItemResolution,
            $location,
            $extensions,
        );
    }

    public static function fromObjectTypeFieldResolutionFeedback(
        ObjectTypeFieldResolutionFeedbackInterface $objectTypeFieldResolutionFeedback,
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $field,
        string|int $objectID,
        ?string $directive
    ): self {
        /** @var ObjectFeedbackInterface[] */
        $nestedObjectFeedbackEntries = [];
        foreach ($objectTypeFieldResolutionFeedback->getNested() as $nestedObjectTypeFieldResolutionFeedback) {
            $nestedObjectFeedbackEntries[] = static::fromObjectTypeFieldResolutionFeedback(
                $nestedObjectTypeFieldResolutionFeedback,
                $relationalTypeResolver,
                $field,
                $objectID,
                $directive
            );
        }
        return new self(
            $objectTypeFieldResolutionFeedback->getFeedbackItemResolution(),
            $objectTypeFieldResolutionFeedback->getLocation(),
            $relationalTypeResolver,
            $field,
            $objectID,
            $directive,
            $objectTypeFieldResolutionFeedback->getExtensions(),
            $nestedObjectFeedbackEntries
        );
    }

    public function getRelationalTypeResolver(): RelationalTypeResolverInterface
    {
        return $this->relationalTypeResolver;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getObjectID(): string|int
    {
        return $this->objectID;
    }

    public function getDirective(): ?string
    {
        return $this->directive;
    }

    /**
     * @return ObjectFeedbackInterface[]
     */
    public function getNested(): array
    {
        return $this->nested;
    }
}
