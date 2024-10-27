<?php

declare(strict_types=1);

namespace PoPCMSSchema\Taxonomies\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPosts\FieldResolvers\ObjectType\AbstractCustomPostListObjectTypeFieldResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\AbstractCustomPostsFilterInputObjectTypeResolver;
use PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType\TaxonomyCustomPostsFilterInputObjectTypeResolver;

abstract class AbstractCustomPostListTaxonomyObjectTypeFieldResolver extends AbstractCustomPostListObjectTypeFieldResolver
{
    private ?TaxonomyCustomPostsFilterInputObjectTypeResolver $taxonomyCustomPostsFilterInputObjectTypeResolver = null;

    final protected function getTaxonomyCustomPostsFilterInputObjectTypeResolver(): TaxonomyCustomPostsFilterInputObjectTypeResolver
    {
        if ($this->taxonomyCustomPostsFilterInputObjectTypeResolver === null) {
            /** @var TaxonomyCustomPostsFilterInputObjectTypeResolver */
            $taxonomyCustomPostsFilterInputObjectTypeResolver = $this->instanceManager->getInstance(TaxonomyCustomPostsFilterInputObjectTypeResolver::class);
            $this->taxonomyCustomPostsFilterInputObjectTypeResolver = $taxonomyCustomPostsFilterInputObjectTypeResolver;
        }
        return $this->taxonomyCustomPostsFilterInputObjectTypeResolver;
    }

    protected function getCustomPostsFilterInputObjectTypeResolver(): AbstractCustomPostsFilterInputObjectTypeResolver
    {
        return $this->getTaxonomyCustomPostsFilterInputObjectTypeResolver();
    }
}
