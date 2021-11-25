<?php

declare(strict_types=1);

namespace PoPSchema\Taxonomies\FieldResolvers\ObjectType;

use PoPSchema\CustomPosts\FieldResolvers\ObjectType\AbstractCustomPostListObjectTypeFieldResolver;
use PoPSchema\CustomPosts\TypeResolvers\InputObjectType\AbstractCustomPostsFilterInputObjectTypeResolver;
use PoPSchema\Taxonomies\TypeResolvers\InputObjectType\TaxonomyCustomPostsFilterInputObjectTypeResolver;

abstract class AbstractCustomPostListTaxonomyObjectTypeFieldResolver extends AbstractCustomPostListObjectTypeFieldResolver
{
    private ?TaxonomyCustomPostsFilterInputObjectTypeResolver $taxonomyCustomPostsFilterInputObjectTypeResolver = null;

    final public function setTaxonomyCustomPostsFilterInputObjectTypeResolver(TaxonomyCustomPostsFilterInputObjectTypeResolver $taxonomyCustomPostsFilterInputObjectTypeResolver): void
    {
        $this->taxonomyCustomPostsFilterInputObjectTypeResolver = $taxonomyCustomPostsFilterInputObjectTypeResolver;
    }
    final protected function getTaxonomyCustomPostsFilterInputObjectTypeResolver(): TaxonomyCustomPostsFilterInputObjectTypeResolver
    {
        return $this->taxonomyCustomPostsFilterInputObjectTypeResolver ??= $this->instanceManager->getInstance(TaxonomyCustomPostsFilterInputObjectTypeResolver::class);
    }

    protected function getCustomPostsFilterInputObjectTypeResolver(): AbstractCustomPostsFilterInputObjectTypeResolver
    {
        return $this->getTaxonomyCustomPostsFilterInputObjectTypeResolver();
    }
}
