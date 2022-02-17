<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Location;

class SchemaFeedback extends AbstractQueryFeedback implements SchemaFeedbackInterface
{
    public function __construct(
        FeedbackResolution $feedbackResolution,
        Location $location,
        protected RelationalTypeResolverInterface $relationalTypeResolver,
        protected string $field,
        /** @var array<string, mixed> */
        array $extensions = [],
        /** @var array<string, mixed> */
        array $data = [],
        /** @var SchemaFeedbackInterface[] */
        protected array $nested = [],
    ) {
        parent::__construct(
            $feedbackResolution,
            $location,
            $extensions,
            $data,
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

    /**
     * @return SchemaFeedbackInterface[]
     */
    public function getNested(): array
    {
        return $this->nested;
    }
}
