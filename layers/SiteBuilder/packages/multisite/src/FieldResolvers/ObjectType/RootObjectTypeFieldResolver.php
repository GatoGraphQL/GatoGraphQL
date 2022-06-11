<?php

declare(strict_types=1);

namespace PoP\Multisite\FieldResolvers\ObjectType;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\Multisite\ObjectModels\Site;
use PoP\Multisite\TypeResolvers\ObjectType\SiteObjectTypeResolver;

class RootObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?SiteObjectTypeResolver $siteObjectTypeResolver = null;
    private ?Site $site = null;

    final public function setSiteObjectTypeResolver(SiteObjectTypeResolver $siteObjectTypeResolver): void
    {
        $this->siteObjectTypeResolver = $siteObjectTypeResolver;
    }
    final protected function getSiteObjectTypeResolver(): SiteObjectTypeResolver
    {
        return $this->siteObjectTypeResolver ??= $this->instanceManager->getInstance(SiteObjectTypeResolver::class);
    }
    final public function setSite(Site $site): void
    {
        $this->site = $site;
    }
    final protected function getSite(): Site
    {
        return $this->site ??= $this->instanceManager->getInstance(Site::class);
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'sites',
            'site',
        ];
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'site' => SchemaTypeModifiers::NON_NULLABLE,
            'sites' => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'sites' => $this->__('All websites', 'multisite'),
            'site' => $this->__('This website', 'multisite'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed> $variables
     * @param array<string, mixed> $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs,
        array $variables,
        array $expressions,
        FieldInterface $field,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
        array $options = []
    ): mixed {
            $root = $object;
        switch ($fieldName) {
            case 'sites':
                return [
                    $this->getSite()->getID(),
                ];
            case 'site':
                return $this->getSite()->getID();
        }

            return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $field, $objectTypeFieldResolutionFeedbackStore, $options);
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'sites',
            'site' =>
                $this->getSiteObjectTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
