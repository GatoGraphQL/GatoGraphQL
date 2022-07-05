<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMeta\FieldResolvers\ObjectType;

use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPCMSSchema\Meta\FieldResolvers\ObjectType\AbstractWithMetaObjectTypeFieldResolver;
use PoPCMSSchema\Meta\TypeAPIs\MetaTypeAPIInterface;
use PoPCMSSchema\Taxonomies\TypeResolvers\ObjectType\AbstractTaxonomyObjectTypeResolver;
use PoPCMSSchema\TaxonomyMeta\TypeAPIs\TaxonomyMetaTypeAPIInterface;

class TaxonomyObjectTypeFieldResolver extends AbstractWithMetaObjectTypeFieldResolver
{
    private ?TaxonomyMetaTypeAPIInterface $taxonomyMetaTypeAPI = null;

    final public function setTaxonomyMetaTypeAPI(TaxonomyMetaTypeAPIInterface $taxonomyMetaTypeAPI): void
    {
        $this->taxonomyMetaTypeAPI = $taxonomyMetaTypeAPI;
    }
    final protected function getTaxonomyMetaTypeAPI(): TaxonomyMetaTypeAPIInterface
    {
        return $this->taxonomyMetaTypeAPI ??= $this->instanceManager->getInstance(TaxonomyMetaTypeAPIInterface::class);
    }

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
        switch ($field->getName()) {
            case 'metaValue':
            case 'metaValues':
                return $this->getTaxonomyMetaTypeAPI()->getTaxonomyTermMeta(
                    $objectTypeResolver->getID($taxonomy),
                    $field->getArgumentValue('key'),
                    $field->getName() === 'metaValue'
                );
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
