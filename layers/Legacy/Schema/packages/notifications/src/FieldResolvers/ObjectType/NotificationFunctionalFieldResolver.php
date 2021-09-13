<?php

declare(strict_types=1);

namespace PoPSchema\Notifications\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\Notifications\TypeResolvers\ObjectType\NotificationTypeResolver;

class NotificationFunctionalFieldResolver extends AbstractObjectTypeFieldResolver
{
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            NotificationTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'multilayoutKeys',
        ];
    }

    public function getSchemaFieldType(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): string
    {
        $types = [
            'multilayoutKeys' => SchemaDefinition::TYPE_STRING,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?int
    {
        return match ($fieldName) {
            'multilayoutKeys' => SchemaTypeModifiers::IS_ARRAY,
            default => parent::getSchemaFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'multilayoutKeys' => $this->translationAPI->__('', ''),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($objectTypeResolver, $fieldName);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $notification = $resultItem;
        switch ($fieldName) {
            case 'multilayoutKeys':
                // If multiple-layouts, then we need 'objectType' and 'action' data-fields
                $object_type = $objectTypeResolver->resolveValue($notification, 'objectType', $variables, $expressions, $options);
                $action = $objectTypeResolver->resolveValue($notification, 'action', $variables, $expressions, $options);
                return array(
                    $object_type . '-' . $action,
                );
        }

        return parent::resolveValue($objectTypeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
