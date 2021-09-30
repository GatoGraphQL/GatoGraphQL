<?php

declare(strict_types=1);

namespace PoPWPSchema\Menus\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPSchema\Menus\TypeResolvers\ObjectType\MenuObjectTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

class RootObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    protected MenuObjectTypeResolver $menuObjectTypeResolver;
    protected StringScalarTypeResolver $stringScalarTypeResolver;

    #[Required]
    public function autowireRootObjectTypeFieldResolver(
        MenuObjectTypeResolver $menuObjectTypeResolver,
        StringScalarTypeResolver $stringScalarTypeResolver,
    ): void {
        $this->menuObjectTypeResolver = $menuObjectTypeResolver;
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
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
            'menuByLocation',
            'menuBySlug',
        ];
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'menuByLocation' => $this->translationAPI->__('Get a menu by its location', 'menus'),
            'menuBySlug' => $this->translationAPI->__('Get a menu by its slug', 'menus'),
            default => parent::getSchemaFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldArgNameResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            default => parent::getSchemaFieldArgNameResolvers($objectTypeResolver, $fieldName),
        };
    }
    
    public function getSchemaFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        return match ([$fieldName => $fieldArgName]) {
            default => parent::getSchemaFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }
    
    public function getSchemaFieldArgDefaultValue(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): mixed
    {
        return match ([$fieldName => $fieldArgName]) {
            default => parent::getSchemaFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }
    
    public function getSchemaFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?int
    {
        return match ([$fieldName => $fieldArgName]) {
            default => parent::getSchemaFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getSchemaFieldArgs(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        switch ($fieldName) {
            case 'menuByLocation':
                return [
                    [
                        SchemaDefinition::ARGNAME_NAME => 'location',
                        SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                        SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The location of the menu', 'menus'),
                        SchemaDefinition::ARGNAME_MANDATORY => true,
                    ],
                ];
            case 'menuBySlug':
                return [
                    [
                        SchemaDefinition::ARGNAME_NAME => 'slug',
                        SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                        SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The slug of the menu', 'menus'),
                        SchemaDefinition::ARGNAME_MANDATORY => true,
                    ],
                ];
        }
        return parent::getSchemaFieldArgs($objectTypeResolver, $fieldName);
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
        $locations = \get_nav_menu_locations();
        switch ($fieldName) {
            case 'menuByLocation':
            case 'menuBySlug':
                $menuParam = null;
                if ($fieldName === 'menuByLocation') {
                    $location = $fieldArgs['location'];
                    $menuParam = $locations[$location] ?? null;
                } elseif ($fieldName === 'menuBySlug') {
                    $menuParam = $fieldArgs['slug'];
                }
                if ($menuParam === null) {
                    return null;
                }
                $menu = wp_get_nav_menu_object($menuParam);
                if ($menu === false) {
                    return null;
                }
                return $menu->term_id;
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'menuByLocation':
            case 'menuBySlug':
                return $this->menuObjectTypeResolver;
        }

        return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
    }
}
