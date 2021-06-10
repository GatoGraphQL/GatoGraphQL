<?php

declare(strict_types=1);

namespace PoPSchema\Posts\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\CustomPosts\Types\Status;
use PoPSchema\Posts\TypeResolvers\PostTypeResolver;

class PostLegacyContentFieldResolver extends AbstractDBDataFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return [
            PostTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'isPublished',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'isPublished' => SchemaDefinition::TYPE_BOOL,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(TypeResolverInterface $typeResolver, string $fieldName): ?int
    {
        $nonNullableFieldNames = [
            'isPublished',
        ];
        if (in_array($fieldName, $nonNullableFieldNames)) {
            return SchemaTypeModifiers::NON_NULLABLE;
        }
        return parent::getSchemaFieldTypeModifiers($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'isPublished' => $this->translationAPI->__('Has the post been published?', 'content'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getSchemaFieldDeprecationDescription(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): ?string
    {
        $placeholder_status = $this->translationAPI->__('Use \'isStatus(status:%s)\' instead of \'%s\'', 'content');
        $descriptions = [
            'isPublished' => sprintf(
                $placeholder_status,
                Status::PUBLISHED,
                $fieldName
            ),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDeprecationDescription($typeResolver, $fieldName, $fieldArgs);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $post = $resultItem;
        switch ($fieldName) {
            case 'isPublished':
                return Status::PUBLISHED == $customPostTypeAPI->getStatus($post);
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
