<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use GraphQLByPoP\GraphQLServer\ObjectModels\TypeInterface;
use PoP\GraphQLParser\Spec\Parser\Location;

class AbstractSchemaFeedback extends AbstractQueryFeedback implements SchemaFeedbackInterface
{
    public function __construct(
        ?string $message = null,
        ?string $code = null,
        ?Location $location = null,
        ?array $data = null,
        ?array $extensions = null,
        protected ?TypeInterface $type = null,
        protected ?string $field = null,
    ) {
        parent::__construct(
            $message,
            $code,
            $location,
            $data,
            $extensions,
        );
    }
    
    public function getType(): ?TypeInterface
    {
        return $this->type;
    }

    public function getField(): ?string
    {
        return $this->field;
    }
}
