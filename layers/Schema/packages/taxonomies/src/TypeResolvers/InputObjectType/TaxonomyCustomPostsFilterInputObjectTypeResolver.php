<?php

declare(strict_types=1);

namespace PoPSchema\Taxonomies\TypeResolvers\InputObjectType;

use PoPSchema\CustomPosts\TypeResolvers\InputObjectType\AbstractCustomPostsFilterInputObjectTypeResolver;

class TaxonomyCustomPostsFilterInputObjectTypeResolver extends AbstractCustomPostsFilterInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'TaxonomyCustomPostsFilterInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->getTranslationAPI()->__('Input to filter a taxonomy\'s custom posts', 'taxonomies');
    }

    protected function addCustomPostInputFields(): bool
    {
        return true;
    }
}
