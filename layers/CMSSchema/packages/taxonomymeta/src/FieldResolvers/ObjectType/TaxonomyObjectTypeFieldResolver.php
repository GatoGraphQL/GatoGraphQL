<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMeta\FieldResolvers\ObjectType;

use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPCMSSchema\Meta\FieldResolvers\ObjectType\AbstractWithMetaObjectTypeFieldResolver;
use PoPCMSSchema\Meta\TypeAPIs\MetaTypeAPIInterface;
use PoPCMSSchema\Taxonomies\TypeResolvers\ObjectType\AbstractTaxonomyObjectTypeResolver;
use PoPCMSSchema\TaxonomyMeta\TypeAPIs\TaxonomyMetaTypeAPIInterface;

class TaxonomyObjectTypeFieldResolver extends AbstractWithMetaObjectTypeFieldResolver
{
    private ?TaxonomyMetaTypeAPIInterface $taxonomyMetaTypeAPI = null;

    final protected function getTaxonomyMetaTypeAPI(): TaxonomyMetaTypeAPIInterface
    {
        if ($this->taxonomyMetaTypeAPI === null) {
            /** @var TaxonomyMetaTypeAPIInterface */
            $taxonomyMetaTypeAPI = $this->instanceManager->getInstance(TaxonomyMetaTypeAPIInterface::class);
            $this->taxonomyMetaTypeAPI = $taxonomyMetaTypeAPI;
        }
        return $this->taxonomyMetaTypeAPI;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractTaxonomyObjectTypeResolver::class,
        ];
    }

    protected function getMetaTypeAPI(): MetaTypeAPIInterface
    {
        return $this->getTaxonomyMetaTypeAPI();
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $taxonomy = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'metaValue':
            case 'metaValues':
                return $this->getTaxonomyMetaTypeAPI()->getTaxonomyTermMeta(
                    $taxonomy,
                    $fieldDataAccessor->getValue('key'),
                    $fieldDataAccessor->getFieldName() === 'metaValue'
                );
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
