<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

trait HasTypeSchemaDefinitionReferenceTrait
{
    protected TypeInterface $type;
    
    public function getType(): TypeInterface
    {
        return $this->type;
    }
}
