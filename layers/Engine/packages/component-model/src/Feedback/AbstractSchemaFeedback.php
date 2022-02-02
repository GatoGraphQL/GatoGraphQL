<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use GraphQLByPoP\GraphQLServer\ObjectModels\TypeInterface;
use PoP\GraphQLParser\Spec\Parser\Location;

class AbstractSchemaFeedback extends AbstractQueryFeedback implements SchemaFeedbackInterface
{
    public function __construct(
        string $message,
        string $code,
        Location $location,
        array $data = [],
        array $extensions = [],
        protected TypeInterface $type,
        /** @var string[] */
        protected array $fields,
    ) {
        parent::__construct(
            $message,
            $code,
            $location,
            $data,
            $extensions,
        );
    }
    
    public function getType(): TypeInterface
    {
        return $this->type;
    }

    /**
     * @return string[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }
}
