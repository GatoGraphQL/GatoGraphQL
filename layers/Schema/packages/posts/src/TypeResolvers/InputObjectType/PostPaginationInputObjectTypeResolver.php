<?php

declare(strict_types=1);

namespace PoPSchema\Posts\TypeResolvers\InputObjectType;

use PoPSchema\CustomPosts\TypeResolvers\InputObjectType\CustomPostPaginationInputObjectTypeResolver;
use PoPSchema\Posts\ComponentConfiguration;

class PostPaginationInputObjectTypeResolver extends CustomPostPaginationInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'PostPaginationInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->getTranslationAPI()->__('Input to paginate posts', 'posts');
    }

    protected function getDefaultLimit(): ?int
    {
        return ComponentConfiguration::getPostListDefaultLimit();
    }

    protected function getMaxLimit(): ?int
    {
        return ComponentConfiguration::getPostListMaxLimit();
    }
}
