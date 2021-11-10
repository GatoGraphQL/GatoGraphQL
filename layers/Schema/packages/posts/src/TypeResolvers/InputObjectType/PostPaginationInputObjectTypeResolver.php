<?php

declare(strict_types=1);

namespace PoPSchema\Posts\TypeResolvers\InputObjectType;

use PoPSchema\Posts\ComponentConfiguration;
use PoPSchema\SchemaCommons\TypeResolvers\InputObjectType\PaginationInputObjectTypeResolver;

class PostPaginationInputObjectTypeResolver extends PaginationInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'PostPaginationInput';
    }

    /**
     * Validate constraints on the input field's value
     *
     * @return string[] Error messages
     */
    protected function getMaxLimit(): ?int
    {
        return ComponentConfiguration::getPostListMaxLimit();
    }
}
