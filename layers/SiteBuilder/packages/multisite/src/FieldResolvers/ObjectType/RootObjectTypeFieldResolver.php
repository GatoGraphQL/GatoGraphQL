<?php

declare(strict_types=1);

namespace PoP\Multisite\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\Multisite\ObjectModels\Site;
use PoP\Multisite\TypeResolvers\ObjectType\SiteObjectTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

class RootObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?SiteObjectTypeResolver $siteObjectTypeResolver = null;
    private ?Site $site = null;

    public function setSiteObjectTypeResolver(SiteObjectTypeResolver $siteObjectTypeResolver): void
    {
        $this->siteObjectTypeResolver = $siteObjectTypeResolver;
    }
    protected function getSiteObjectTypeResolver(): SiteObjectTypeResolver
    {
        return $this->siteObjectTypeResolver ??= $this->instanceManager->getInstance(SiteObjectTypeResolver::class);
    }
    public function setSite(Site $site): void
    {
        $this->site = $site;
    }
    protected function getSite(): Site
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
            'sites' => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'sites' => $this->getTranslationAPI()->__('All websites', 'multisite'),
            'site' => $this->getTranslationAPI()->__('This website', 'multisite'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
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

            return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
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
