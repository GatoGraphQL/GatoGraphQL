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
    protected SiteObjectTypeResolver $siteObjectTypeResolver;
    protected Site $site;

    #[Required]
    final public function autowireRootObjectTypeFieldResolver(
        SiteObjectTypeResolver $siteObjectTypeResolver,
        Site $site,
    ): void {
        $this->siteObjectTypeResolver = $siteObjectTypeResolver;
        $this->site = $site;
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
            'sites' => $this->translationAPI->__('All websites', 'multisite'),
            'site' => $this->translationAPI->__('This website', 'multisite'),
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
                        $this->site->getID(),
                    ];
                case 'site':
                    return $this->site->getID();
            }
    
            return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
        }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'sites',
            'site' =>
                $this->siteObjectTypeResolver,
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
