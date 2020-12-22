<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

trait NonDocumentableTypeTrait
{
    /**
     * Always return null since the description will not apply to the return type
     * (eg: a scalar is used as the type from a field, and the description belongs to the field, not to the type)
     *
     * @return string
     */
    public function getDescription(): ?string
    {
        return null;
    }
}
