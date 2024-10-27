<?php

declare(strict_types=1);

namespace PoPWPSchema\Multisite\FieldResolvers\ObjectType;

use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPWPSchema\Multisite\TypeAPIs\MultisiteTypeAPIInterface;
use PoPWPSchema\Multisite\TypeResolvers\ObjectType\NetworkSiteObjectTypeResolver;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

class RootObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    private ?IntScalarTypeResolver $intScalarTypeResolver = null;
    private ?NetworkSiteObjectTypeResolver $networkSiteObjectTypeResolver = null;
    private ?MultisiteTypeAPIInterface $multisiteTypeAPI = null;

    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        if ($this->intScalarTypeResolver === null) {
            /** @var IntScalarTypeResolver */
            $intScalarTypeResolver = $this->instanceManager->getInstance(IntScalarTypeResolver::class);
            $this->intScalarTypeResolver = $intScalarTypeResolver;
        }
        return $this->intScalarTypeResolver;
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
    final protected function getMultisiteTypeAPI(): MultisiteTypeAPIInterface
    {
        if ($this->multisiteTypeAPI === null) {
            /** @var MultisiteTypeAPIInterface */
            $multisiteTypeAPI = $this->instanceManager->getInstance(MultisiteTypeAPIInterface::class);
            $this->multisiteTypeAPI = $multisiteTypeAPI;
        }
        return $this->multisiteTypeAPI;
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
            'networkSiteCount',
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'networkSites' => $this->__('Sites in the WordPress multisite network', 'multisite'),
            'networkSiteCount' => $this->__('Number of sites in the WordPress multisite network', 'multisite'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'networkSites' => $this->getNetworkSiteObjectTypeResolver(),
            'networkSiteCount' => $this->getIntScalarTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'networkSites' => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            'networkSiteCount' => SchemaTypeModifiers::NON_NULLABLE,
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
                /** @var int[] */
                $siteIDs = $this->getMultisiteTypeAPI()->getNetworkSites([], [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
                return $siteIDs;
            case 'networkSiteCount':
                /** @var int[] */
                $siteIDs = $this->getMultisiteTypeAPI()->getNetworkSiteCount([], [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
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
