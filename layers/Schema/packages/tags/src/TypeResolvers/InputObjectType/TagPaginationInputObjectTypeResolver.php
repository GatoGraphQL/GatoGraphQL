<?php

declare(strict_types=1);

namespace PoPSchema\Tags\TypeResolvers\InputObjectType;

use PoPSchema\Tags\Component;
use PoPSchema\Tags\ComponentConfiguration;
use PoPSchema\SchemaCommons\TypeResolvers\InputObjectType\PaginationInputObjectTypeResolver;

class TagPaginationInputObjectTypeResolver extends PaginationInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'TagPaginationInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->getTranslationAPI()->__('Input to paginate tags', 'tags');
    }

    protected function getDefaultLimit(): ?int
    {
        return ComponentConfiguration::getTagListDefaultLimit();
    }

    protected function getMaxLimit(): ?int
    {
        return ComponentConfiguration::getTagListMaxLimit();
    }
}
