<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\TypeResolvers\InputObjectType;

use PoPSchema\CustomPosts\Component;
use PoPSchema\CustomPosts\ComponentConfiguration;
use PoPSchema\SchemaCommons\TypeResolvers\InputObjectType\PaginationInputObjectTypeResolver;

class CustomPostPaginationInputObjectTypeResolver extends PaginationInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'CustomPostPaginationInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->getTranslationAPI()->__('Input to paginate custom posts', 'customposts');
    }

    protected function getDefaultLimit(): ?int
    {
        return ComponentConfiguration::getCustomPostListDefaultLimit();
    }

    protected function getMaxLimit(): ?int
    {
        return ComponentConfiguration::getCustomPostListMaxLimit();
    }
}
