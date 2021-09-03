<?php

declare(strict_types=1);

namespace PoPSchema\BlockMetadataWP\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ObjectTypeResolverInterface;
use PoPSchema\Posts\TypeResolvers\PostTypeResolver;

class TryNewFeaturesPostFieldResolver extends AbstractDBDataFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return [
            PostTypeResolver::class,
        ];
    }

    public function resolveCanProcess(ObjectTypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): bool
    {
        return ($fieldArgs['branch'] ?? null) == 'try-new-features' && ($fieldArgs['project'] ?? null) == 'block-metadata';
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'content',
        ];
    }

    public function getSchemaFieldType(ObjectTypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'content' => SchemaDefinition::TYPE_STRING,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $typeResolver, string $fieldName): ?int
    {
        switch ($fieldName) {
            case 'content':
                return SchemaTypeModifiers::NON_NULLABLE;
        }
        return parent::getSchemaFieldTypeModifiers($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'content' => $this->translationAPI->__('Post\'s content, formatted with its block metadata', 'pop-block-metadata'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
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
        switch ($fieldName) {
            case 'content':
                unset($fieldArgs['branch']);
                unset($fieldArgs['project']);
                return $typeResolver->resolveValue(
                    $resultItem,
                    $this->fieldQueryInterpreter->getField('blockMetadata', $fieldArgs),
                    $variables,
                    $expressions,
                    $options
                );
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
