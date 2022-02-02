<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use GraphQLByPoP\GraphQLServer\ObjectModels\TypeInterface;
use PoP\GraphQLParser\Spec\Parser\Location;

class AbstractObjectFeedback extends AbstractSchemaFeedback implements ObjectFeedbackInterface
{
    public function __construct(
        string $message,
        string $code,
        Location $location,
        array $data = [],
        array $extensions = [],
        TypeInterface $type,
        string $field,
        protected string|int $objectID,
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
    
    public function getObjectID(): string|int
    {
        return $this->objectID;
    }
}
