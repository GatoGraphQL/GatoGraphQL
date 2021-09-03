<?php

declare(strict_types=1);

namespace PoPWPSchema\CustomPosts\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ObjectTypeResolverInterface;
use PoPSchema\CustomPosts\FieldInterfaceResolvers\IsCustomPostFieldInterfaceResolver;
use WP_Post;

class CustomPostFieldResolver extends AbstractQueryableFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return [
            IsCustomPostFieldInterfaceResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'isSticky',
        ];
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'isSticky' => $this->translationAPI->__('Determines whether a custom post is sticky', 'customposts'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getSchemaFieldType(ObjectTypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'isSticky' => SchemaDefinition::TYPE_BOOL,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $typeResolver, string $fieldName): ?int
    {
        return match ($fieldName) {
            'isSticky' => SchemaTypeModifiers::NON_NULLABLE,
            default => parent::getSchemaFieldTypeModifiers($typeResolver, $fieldName),
        };
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        /** @var WP_Post */
        $customPost = $resultItem;
        switch ($fieldName) {
            case 'isSticky':
                return \is_sticky($customPost->ID);
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
