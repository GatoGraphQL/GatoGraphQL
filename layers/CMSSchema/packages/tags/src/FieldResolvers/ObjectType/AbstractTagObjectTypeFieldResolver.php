<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\FieldResolvers\ObjectType;

use PoPCMSSchema\QueriedObject\FieldResolvers\InterfaceType\QueryableInterfaceTypeFieldResolver;
use PoPCMSSchema\Tags\FieldResolvers\InterfaceType\TagInterfaceTypeFieldResolver;
use PoPCMSSchema\Tags\ModuleContracts\TagAPIRequestedContractObjectTypeFieldResolverInterface;
use PoPCMSSchema\Tags\TypeAPIs\UniversalTagTypeAPIInterface;
use PoPCMSSchema\Taxonomies\TypeAPIs\TaxonomyTermTypeAPIInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

abstract class AbstractTagObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver implements TagAPIRequestedContractObjectTypeFieldResolverInterface
{
    private ?IntScalarTypeResolver $intScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?QueryableInterfaceTypeFieldResolver $queryableInterfaceTypeFieldResolver = null;
    private ?TagInterfaceTypeFieldResolver $tagInterfaceTypeFieldResolver = null;
    private ?UniversalTagTypeAPIInterface $universalTagTypeAPI = null;
    private ?TaxonomyTermTypeAPIInterface $taxonomyTermTypeAPI = null;

    final public function setIntScalarTypeResolver(IntScalarTypeResolver $intScalarTypeResolver): void
    {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
    }
    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        if ($this->intScalarTypeResolver === null) {
            /** @var IntScalarTypeResolver */
            $intScalarTypeResolver = $this->instanceManager->getInstance(IntScalarTypeResolver::class);
            $this->intScalarTypeResolver = $intScalarTypeResolver;
        }
        return $this->intScalarTypeResolver;
    }
    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        if ($this->stringScalarTypeResolver === null) {
            /** @var StringScalarTypeResolver */
            $stringScalarTypeResolver = $this->instanceManager->getInstance(StringScalarTypeResolver::class);
            $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        }
        return $this->stringScalarTypeResolver;
    }
    final public function setQueryableInterfaceTypeFieldResolver(QueryableInterfaceTypeFieldResolver $queryableInterfaceTypeFieldResolver): void
    {
        $this->queryableInterfaceTypeFieldResolver = $queryableInterfaceTypeFieldResolver;
    }
    final protected function getQueryableInterfaceTypeFieldResolver(): QueryableInterfaceTypeFieldResolver
    {
        if ($this->queryableInterfaceTypeFieldResolver === null) {
            /** @var QueryableInterfaceTypeFieldResolver */
            $queryableInterfaceTypeFieldResolver = $this->instanceManager->getInstance(QueryableInterfaceTypeFieldResolver::class);
            $this->queryableInterfaceTypeFieldResolver = $queryableInterfaceTypeFieldResolver;
        }
        return $this->queryableInterfaceTypeFieldResolver;
    }
    final public function setTagInterfaceTypeFieldResolver(TagInterfaceTypeFieldResolver $tagInterfaceTypeFieldResolver): void
    {
        $this->tagInterfaceTypeFieldResolver = $tagInterfaceTypeFieldResolver;
    }
    final protected function getTagInterfaceTypeFieldResolver(): TagInterfaceTypeFieldResolver
    {
        if ($this->tagInterfaceTypeFieldResolver === null) {
            /** @var TagInterfaceTypeFieldResolver */
            $tagInterfaceTypeFieldResolver = $this->instanceManager->getInstance(TagInterfaceTypeFieldResolver::class);
            $this->tagInterfaceTypeFieldResolver = $tagInterfaceTypeFieldResolver;
        }
        return $this->tagInterfaceTypeFieldResolver;
    }
    final public function setUniversalTagTypeAPI(UniversalTagTypeAPIInterface $universalTagTypeAPI): void
    {
        $this->universalTagTypeAPI = $universalTagTypeAPI;
    }
    final protected function getUniversalTagTypeAPI(): UniversalTagTypeAPIInterface
    {
        if ($this->universalTagTypeAPI === null) {
            /** @var UniversalTagTypeAPIInterface */
            $universalTagTypeAPI = $this->instanceManager->getInstance(UniversalTagTypeAPIInterface::class);
            $this->universalTagTypeAPI = $universalTagTypeAPI;
        }
        return $this->universalTagTypeAPI;
    }
    final public function setTaxonomyTermTypeAPI(TaxonomyTermTypeAPIInterface $taxonomyTermTypeAPI): void
    {
        $this->taxonomyTermTypeAPI = $taxonomyTermTypeAPI;
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

    /**
     * @return array<InterfaceTypeFieldResolverInterface>
     */
    public function getImplementedInterfaceTypeFieldResolvers(): array
    {
        return [
            $this->getQueryableInterfaceTypeFieldResolver(),
            $this->getTagInterfaceTypeFieldResolver(),
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            // Queryable interface
            'url',
            'urlPath',
            'slug',

            // Tag interface
            'name',
            'description',
            'count',

            // Own
            'taxonomy',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'taxonomy' => $this->getTaxonomyFieldTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    abstract protected function getTaxonomyFieldTypeResolver(): ConcreteTypeResolverInterface;

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'url' => $this->__('Tag URL', 'pop-tags'),
            'urlPath' => $this->__('Tag URL path', 'pop-tags'),
            'slug' => $this->__('Tag slug', 'pop-tags'),
            'taxonomy' => $this->__('Tag taxonomy', 'pop-tags'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'taxonomy' => SchemaTypeModifiers::NON_NULLABLE,
            default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $tag = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'taxonomy':
                return $this->getTaxonomyTermTypeAPI()->getTermTaxonomyName($tag);

            case 'url':
                /** @var string */
                return $this->getUniversalTagTypeAPI()->getTagURL($tag);

            case 'urlPath':
                /** @var string */
                return $this->getUniversalTagTypeAPI()->getTagURLPath($tag);

            case 'name':
                /** @var string */
                return $this->getUniversalTagTypeAPI()->getTagName($tag);

            case 'slug':
                /** @var string */
                return $this->getUniversalTagTypeAPI()->getTagSlug($tag);

            case 'description':
                /** @var string */
                return $this->getUniversalTagTypeAPI()->getTagDescription($tag);

            case 'count':
                /** @var int */
                return $this->getUniversalTagTypeAPI()->getTagItemCount($tag);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }

    /**
     * Since the return type is known for all the fields in this
     * FieldResolver, there's no need to validate them
     */
    public function validateResolvedFieldType(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
    ): bool {
        return false;
    }
}
