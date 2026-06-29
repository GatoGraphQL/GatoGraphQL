<?php

declare(strict_types=1);

namespace PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType;

use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\AbstractCustomPostsFilterInputObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\CustomPostsFilterInputObjectTypeResolverInterface;

class TaxonomyCustomPostsFilterInputObjectTypeResolver extends AbstractCustomPostsFilterInputObjectTypeResolver implements CustomPostsFilterInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'TaxonomyCustomPostsFilterInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to filter a taxonomy\'s custom posts', 'gatographql');
    }

    protected function addCustomPostInputFields(): bool
    {
        return true;
    }
}
