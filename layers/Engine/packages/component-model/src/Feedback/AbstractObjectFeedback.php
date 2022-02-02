<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use GraphQLByPoP\GraphQLServer\ObjectModels\TypeInterface;
use PoP\GraphQLParser\Spec\Parser\Location;

class AbstractObjectFeedback extends AbstractSchemaFeedback implements ObjectFeedbackInterface
{
    public function __construct(
        ?string $message = null,
        ?string $code = null,
        ?Location $location = null,
        ?array $data = null,
        ?array $extensions = null,
        ?TypeInterface $type = null,
        ?string $field = null,
        protected string|int|null $objectID = null,
    ) {
        parent::__construct(
            $message,
            $code,
            $location,
            $data,
            $extensions,
            $type,
            $field,
        );
    }
    
    public function getObjectID(): string|int|null
    {
        return $this->objectID;
    }
}
