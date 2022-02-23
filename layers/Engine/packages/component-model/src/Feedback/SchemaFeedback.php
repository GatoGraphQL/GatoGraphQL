<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Location;

class SchemaFeedback extends AbstractQueryFeedback implements SchemaFeedbackInterface
{
    public function __construct(
        FeedbackItemResolution $feedbackItemResolution,
        Location $location,
        protected RelationalTypeResolverInterface $relationalTypeResolver,
        protected string $field,
        protected ?string $directive = null,
        /** @var array<string, mixed> */
        array $extensions = [],
        /** @var array<string, mixed> */
        array $data = [],
        /** @var SchemaFeedbackInterface[] */
        protected array $nested = [],
    ) {
        parent::__construct(
            $feedbackItemResolution,
            $location,
            $extensions,
            $data,
        );
    }

    public static function fromObjectTypeFieldResolutionFeedback(
        ObjectTypeFieldResolutionFeedbackInterface $objectTypeFieldResolutionFeedback,
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $field,
        ?string $directive,
    ): self {
        /** @var SchemaFeedbackInterface[] */
        $nestedSchemaFeedbackEntries = [];
        foreach ($objectTypeFieldResolutionFeedback->getNested() as $nestedObjectTypeFieldResolutionFeedback) {
            $nestedSchemaFeedbackEntries[] = static::fromObjectTypeFieldResolutionFeedback(
                $nestedObjectTypeFieldResolutionFeedback,
                $relationalTypeResolver,
                $field,
                $directive
            );
        }
        return new self(
            $objectTypeFieldResolutionFeedback->getFeedbackItemResolution(),
            $objectTypeFieldResolutionFeedback->getLocation(),
            $relationalTypeResolver,
            $field,
            $directive,
            $objectTypeFieldResolutionFeedback->getExtensions(),
            $objectTypeFieldResolutionFeedback->getData(),
            $nestedSchemaFeedbackEntries
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

    public function getDirective(): ?string
    {
        return $this->directive;
    }

    /**
     * @return SchemaFeedbackInterface[]
     */
    public function getNested(): array
    {
        return $this->nested;
    }
}
