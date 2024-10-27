<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPosts\FieldResolvers\ObjectType\AbstractCustomPostListObjectTypeFieldResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\AbstractCustomPostsFilterInputObjectTypeResolver;
use PoPCMSSchema\Tags\TypeResolvers\ObjectType\GenericTagObjectTypeResolver;
use PoPCMSSchema\Taxonomies\TypeAPIs\TaxonomyTermTypeAPIInterface;
use PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType\TaxonomyCustomPostsFilterInputObjectTypeResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class GenericTagListObjectTypeFieldResolver extends AbstractCustomPostListObjectTypeFieldResolver
{
    private ?TaxonomyCustomPostsFilterInputObjectTypeResolver $taxonomyCustomPostsFilterInputObjectTypeResolver = null;
    private ?TaxonomyTermTypeAPIInterface $taxonomyTermTypeAPI = null;

    final protected function getTaxonomyCustomPostsFilterInputObjectTypeResolver(): TaxonomyCustomPostsFilterInputObjectTypeResolver
    {
        if ($this->taxonomyCustomPostsFilterInputObjectTypeResolver === null) {
            /** @var TaxonomyCustomPostsFilterInputObjectTypeResolver */
            $taxonomyCustomPostsFilterInputObjectTypeResolver = $this->instanceManager->getInstance(TaxonomyCustomPostsFilterInputObjectTypeResolver::class);
            $this->taxonomyCustomPostsFilterInputObjectTypeResolver = $taxonomyCustomPostsFilterInputObjectTypeResolver;
        }
        return $this->taxonomyCustomPostsFilterInputObjectTypeResolver;
    }
    final protected function getTaxonomyTermTypeAPI(): TaxonomyTermTypeAPIInterface
    {
        if ($this->taxonomyTermTypeAPI === null) {
            /** @var TaxonomyTermTypeAPIInterface */
            $taxonomyTermTypeAPI = $this->instanceManager->getInstance(TaxonomyTermTypeAPIInterface::class);
            $this->taxonomyTermTypeAPI = $taxonomyTermTypeAPI;
        }
        return $this->taxonomyTermTypeAPI;
    }

    protected function getCustomPostsFilterInputObjectTypeResolver(): AbstractCustomPostsFilterInputObjectTypeResolver
    {
        return $this->getTaxonomyCustomPostsFilterInputObjectTypeResolver();
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            GenericTagObjectTypeResolver::class,
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'customPosts' => $this->__('Custom posts which contain this tag', 'pop-taxonomies'),
            'customPostCount' => $this->__('Number of custom posts which contain this tag', 'pop-taxonomies'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @return array<string,mixed>
     */
    protected function getQuery(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
    ): array {
        $query = parent::getQuery($objectTypeResolver, $object, $fieldDataAccessor);

        $tag = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'customPosts':
            case 'customPostCount':
                $query['tag-ids'] = [$objectTypeResolver->getID($tag)];
                $query['tag-taxonomy'] = $this->getTaxonomyTermTypeAPI()->getTermTaxonomyName($tag);
                break;
        }

        return $query;
    }
}
