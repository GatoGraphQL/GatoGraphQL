<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Location;

class SchemaFeedback extends QueryFeedback implements SchemaFeedbackInterface
{
    public function __construct(
        string $message,
        string $code,
        Location $location,
        protected RelationalTypeResolverInterface $relationalTypeResolver,
        /** @var string[] */
        protected array $fields,
        /** @var array<string, mixed> */
        array $data = [],
        /** @var array<string, mixed> */
        array $extensions = [],
    ) {
        parent::__construct(
            $message,
            $code,
            $location,
            $data,
            $extensions,
        );
    }

    public function getRelationalTypeResolver(): RelationalTypeResolverInterface
    {
        return $this->relationalTypeResolver;
    }

    /**
     * @return string[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }
}
