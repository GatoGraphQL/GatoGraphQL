<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Location;

class ObjectFeedback extends AbstractQueryFeedback implements ObjectFeedbackInterface
{
    public function __construct(
        string $message,
        ?string $code,
        Location $location,
        protected RelationalTypeResolverInterface $relationalTypeResolver,
        protected string $field,
        protected string|int $objectID,
        /** @var array<string, mixed> */
        array $extensions = [],
        /** @var array<string, mixed> */
        array $data = [],
    ) {
        parent::__construct(
            $message,
            $code,
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

    public function getObjectID(): string|int
    {
        return $this->objectID;
    }
}
