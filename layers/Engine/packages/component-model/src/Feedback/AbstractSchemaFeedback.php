<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Location;

class AbstractSchemaFeedback extends AbstractQueryFeedback implements SchemaFeedbackInterface
{
    public function __construct(
        string $message,
        string $code,
        Location $location,
        protected RelationalTypeResolverInterface $relationalTypeResolver,
        /** @var string[] */
        protected array $fields,
        array $data = [],
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
