<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

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

    /**
     * @return ObjectTypeFieldResolutionFeedbackInterface[]
     */
    public function getNested(): array
    {
        return $this->nested;
    }
}
