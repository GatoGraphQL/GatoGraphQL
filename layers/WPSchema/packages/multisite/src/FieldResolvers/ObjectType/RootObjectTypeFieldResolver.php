<?php

declare(strict_types=1);

namespace PoPWPSchema\Multisite\FieldResolvers\ObjectType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

class RootObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    private ?NetworkSiteObjectTypeResolver $networkSiteObjectTypeResolver = null;

    final public function setNetworkSiteObjectTypeResolver(NetworkSiteObjectTypeResolver $networkSiteObjectTypeResolver): void
    {
        $this->networkSiteObjectTypeResolver = $networkSiteObjectTypeResolver;
    }
    final protected function getNetworkSiteObjectTypeResolver(): NetworkSiteObjectTypeResolver
    {
        if ($this->networkSiteObjectTypeResolver === null) {
            /** @var NetworkSiteObjectTypeResolver */
            $networkSiteObjectTypeResolver = $this->instanceManager->getInstance(NetworkSiteObjectTypeResolver::class);
            $this->networkSiteObjectTypeResolver = $networkSiteObjectTypeResolver;
        }
        return $this->networkSiteObjectTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootObjectTypeResolver::class,
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'networkSites',
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'networkSites' => $this->__('Sites in the WordPress multisite network', 'multisite'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'networkSites' => $this->getNetworkSiteObjectTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'networkSites' => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        switch ($fieldDataAccessor->getFieldName()) {
            case 'networkSites':
                $args = [
                    'fields' => 'ids',
                    'number' => '',
                    'archived' => 0,
                    'spam' => 0,
                    'deleted' => 0,
                ];
        
                /** @var int[] */
                $siteIDs = get_sites($args);
                return $siteIDs;
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
